<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Test\Account;

use DiDom\Document;
use DOMElement;
use Madsoft\Library\Config;
use Madsoft\Library\Crud;
use Madsoft\Library\Folders;
use Madsoft\Library\Mailer;
use Madsoft\Library\Responder\Account\AccountConfig;
use Madsoft\Library\Session;
use Madsoft\Library\Test\LibraryTestCleaner;
use Madsoft\Library\Tester\Test;
use RuntimeException;
use SplFileInfo;

/**
 * AccountTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class AccountTest extends Test
{
    const EMAIL = 'tester@testing.com';
    const PASSWORD_FIRST = 'First1234!';
    const PASSWORD = 'Pass1234!';

    protected Session $session;
    protected Folders $folders;
    protected Crud $crud;
    protected Config $config;
    protected LibraryTestCleaner $cleaner;
    
    /**
     * Method __construct
     *
     * @param Session            $session session
     * @param Folders            $folders folders
     * @param Crud               $crud    crud
     * @param Config             $config  config
     * @param LibraryTestCleaner $cleaner cleaner
     */
    public function __construct(
        Session $session,
        Folders $folders,
        Crud $crud,
        Config $config,
        LibraryTestCleaner $cleaner
    ) {
        $this->session = $session;
        $this->folders = $folders;
        $this->crud = $crud;
        $this->config = $config;
        $this->cleaner = $cleaner;
    }

    /**
     * Method testRoutes
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testRoutes(): void
    {
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['public']['GET']['login']
        );
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['public']['GET']['registry']
        );
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['public']['GET']['resend']
        );
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['public']['GET']['activate']
        );
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['public']['GET']['reset']
        );
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['public']['POST']['login']
        );
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['public']['POST']['registry']
        );
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['public']['POST']['reset']
        );
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['public']['POST']['change']
        );
        $this->assertNotEquals(
            null,
            AccountConfig::ROUTES['protected']['GET']['logout']
        );
    }

    /**
     * Method testLogin
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testAccount(): void
    {
        $this->canSeeLogin();
        $this->canSeePublicIndex();
        
        $this->canSeeLoginFails();
        $this->canSeePublicIndex();
        
        $this->canSeeRegistry();
        $this->canSeePublicIndex();
        
        $this->canSeeRegistryFails();
        $this->canSeePublicIndex();
        
        $this->canSeeRegistryWorks();
        $this->canSeePublicIndex();
        
        $this->canSeeActivationMail();
        $this->canSeePublicIndex();
        
        $this->canSeeResendWorks();
        $this->canSeePublicIndex();
        
        $this->canSeeActivationMail();
        $this->canSeePublicIndex();
        
        $this->canSeeRegistryUserAlreadyRegisteredFail();
        $this->canSeePublicIndex();
        
        $this->canSeeActivationFails();
        $this->canSeePublicIndex();
        
        $this->canSeeActivationWorks();
        $this->canSeePublicIndex();
        
        $this->canSeeActivationUserAlreadyActiveFail();
        $this->canSeePublicIndex();
        
        $this->canSeeLoginWorks(self::PASSWORD_FIRST);
        $this->canSeeProtectedIndex();
        
        $this->canSeeLogoutWorks();
        $this->canSeePublicIndex();
        
        $this->canSeeResetPassword();
        $this->canSeePublicIndex();
        
        $this->canSeeResetPasswordFails();
        $this->canSeePublicIndex();
        
        $this->canSeeResetPasswordWorks();
        $this->canSeePublicIndex();
        
        $this->canSeeNewPasswordFails();
        $this->canSeePublicIndex();
        
        $this->canSeeNewPassword();
        $this->canSeePublicIndex();
        
        $this->canSeeNewPasswordWorks();
        $this->canSeePublicIndex();
        
        $this->canSeeLoginWorks();
        $this->canSeeProtectedIndex();
        
        $this->canSeeLogoutWorks();
        $this->canSeePublicIndex();
    }
    
    /**
     * Method canSeePublicIndex
     *
     * @return void
     */
    protected function canSeePublicIndex(): void
    {
        $contents = $this->get('q=example');
        $this->assertStringContains('Public Index Page', $contents);
    }
    
    /**
     * Method canSeeProtectedIndex
     *
     * @return void
     */
    protected function canSeeProtectedIndex(): void
    {
        $contents = $this->get('q=example');
        $this->assertStringContains('Restricted Index Page', $contents);
    }
        
    /**
     * Method canSeeLogin
     *
     * @return void
     */
    protected function canSeeLogin(): void
    {
        $contents = $this->get('q=login');
        $this->assertStringContains('Login', $contents);
        // TODO check if correct form exists
    }
    
    /**
     * Method canSeeLoginFails
     *
     * @return void
     */
    protected function canSeeLoginFails(): void
    {
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
            //                'email' => self::EMAIL,
            //                'password' => self::PASSWORD,
            ]
        );
        $this->assertStringContains('Login failed', $contents);
        
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
            //                'password' => self::PASSWORD,
            ]
        );
        $this->assertStringContains('Login failed', $contents);
        
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
            //                'email' => self::EMAIL,
                'password' => self::PASSWORD_FIRST,
            ]
        );
        $this->assertStringContains('Login failed', $contents);
        
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => '',
                'password' => '',
            ]
        );
        $this->assertStringContains('Login failed', $contents);
        
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
                'password' => self::PASSWORD_FIRST,
            ]
        );
        $this->assertStringContains('Login failed', $contents);
    }
    
    /**
     * Method canSeeRegistry
     *
     * @return void
     */
    protected function canSeeRegistry(): void
    {
        $contents = $this->get('q=registry');
        $this->assertStringContains('Registration', $contents);
        // TODO check if correct form exists
    }
    
    /**
     * Method canSeeRegistryFails
     *
     * @return void
     */
    protected function canSeeRegistryFails(): void
    {
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
            //                'email' => '',
            //                'email_retype' => '',
            //                'password' => '',
            ]
        );
        $this->assertStringContains('Invalid registration data', $contents);
        $this->assertStringContains('Invalid email format', $contents);
        $this->assertStringContains('Invalid password', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => '',
                'email_retype' => '',
                'password' => '',
            ]
        );
        $this->assertStringContains('Invalid registration data', $contents);
        $this->assertStringContains('Invalid email format', $contents);
        $this->assertStringContains('Invalid password', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'itisnotvalid',
                'email_retype' => '',
                'password' => '',
            ]
        );
        $this->assertStringContains('Invalid registration data', $contents);
        $this->assertStringContains('Invalid email format', $contents);
        $this->assertStringContains('Invalid password', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'valid@email.com',
                'email_retype' => 'wrong@retype.com',
                'password' => '',
            ]
        );
        $this->assertStringContains('Invalid registration data', $contents);
        $this->assertStringContains('Doesn\'t match', $contents);
        $this->assertStringContains('Invalid password', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'valid@email.com',
                'email_retype' => 'valid@email.com',
                'password' => 'short',
            ]
        );
        $this->assertStringContains('Invalid registration data', $contents);
        $this->assertStringContains('Invalid password', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'valid@email.com',
                'email_retype' => 'valid@email.com',
                'password' => 'longbutdoesnothavenumber',
            ]
        );
        $this->assertStringContains('Invalid registration data', $contents);
        $this->assertStringContains('Invalid password', $contents);
        
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'valid@email.com',
                'email_retype' => 'valid@email.com',
                'password' => 'nospecchar123',
            ]
        );
        $this->assertStringContains('Invalid registration data', $contents);
        $this->assertStringContains('Invalid password', $contents);
    }

    /**
     * Method canSeeRegistryWorks
     *
     * @return void
     */
    protected function canSeeRegistryWorks(): void
    {
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
                'email_retype' => self::EMAIL,
                'password' => self::PASSWORD_FIRST,
            ]
        );
        $this->assertStringContains('Activate your account', $contents);
        $this->assertStringContains('We sent an activation email', $contents);
    }
    
    /**
     * Method canSeeResendWorks
     *
     * @return void
     */
    protected function canSeeResendWorks(): void
    {
        $this->cleaner->deleteMails();
        $contents = $this->get('q=resend');
        $this->assertStringContains('Activate your account', $contents);
        $this->assertStringContains('We re-sent an activation email', $contents);
    }
    
    
    /**
     * Method canSeeRegistryUserAlreadyRegisteredFail
     *
     * @return void
     */
    protected function canSeeRegistryUserAlreadyRegisteredFail(): void
    {
        $contents = $this->post(
            'q=registry',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
                'email_retype' => self::EMAIL,
                'password' => self::PASSWORD_FIRST,
            ]
        );
        $this->assertStringContains('Email address already registered', $contents);
    }
    
    /**
     * Method canSeeActivationMail
     *
     * @return void
     */
    protected function canSeeActivationMail(): void
    {
        $emailFilename = $this->getLastEmailFilename();
        $this->assertStringContains(self::EMAIL, $emailFilename);
        $this->assertStringContains('Activate your account', $emailFilename);
        
        $user = $this->crud->getRow(
            'user',
            ['token'],
            ['email' => self::EMAIL]
        );
        $activationLink = $this->config->get('Site')->get('base')
                . '?q=activate&token=' . ($user['token'] ?? '');
        $links = (new Document($emailFilename, true))->find('a');
        $found = false;
        foreach ($links as $link) {
            if ($link instanceof DOMElement) {
                throw new RuntimeException('Invalid DOM context');
            }
            $href = $link->attr('href');
            if ($href === $activationLink) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }
    
    /**
     * Method canSeeActivationFails
     *
     * @return void
     */
    protected function canSeeActivationFails(): void
    {
        $contents = $this->get('q=activate');
        $this->assertStringContains('Account activation failed', $contents);
        $this->assertStringContains('Mandatory', $contents);
        
        $contents = $this->get('q=activate&token=wrong-token');
        $this->assertStringContains('Invalid token', $contents);
    }
    
    /**
     * Method canSeeActivationWorks
     *
     * @return void
     */
    protected function canSeeActivationWorks(): void
    {
        $user = $this->crud->getRow(
            'user',
            ['token'],
            ['email' => self::EMAIL]
        );
        $contents = $this->get('q=activate&token=' . ($user['token'] ?? ''));
        $this->assertStringContains('Account is now activated', $contents);
    }
    
    /**
     * Method canSeeActivationUserAlreayActiveFail
     *
     * @return void
     */
    protected function canSeeActivationUserAlreadyActiveFail(): void
    {
        $user = $this->crud->getRow(
            'user',
            ['token'],
            ['email' => self::EMAIL]
        );
        $contents = $this->get('q=activate&token=' . ($user['token'] ?? ''));
        $this->assertStringContains('User is active already', $contents);
    }
    
    /**
     * Method canSeeLoginWorks
     *
     * @param string|null $password password
     *
     * @return void
     */
    protected function canSeeLoginWorks(?string $password = null): void
    {
        $contents = $this->post(
            'q=login',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => $this::EMAIL,
                'password' => null === $password ? $this::PASSWORD : $password,
            ]
        );
        $this->assertStringContains('Login success', $contents);
    }
    
    /**
     * Method canSeeLogoutWorks
     *
     * @return void
     */
    protected function canSeeLogoutWorks(): void
    {
        $contents = $this->get('q=logout');
        $this->assertStringContains('Logout success', $contents);
    }
    
    /**
     * Method canSeeResetPassword
     *
     * @return void
     */
    protected function canSeeResetPassword(): void
    {
        $contents = $this->get('q=reset');
        $this->assertStringContains('Password reset', $contents);
        // TODO check if correct form exists
    }
    
    /**
     * Method canSeeResetPasswordFails
     *
     * @return void
     */
    protected function canSeeResetPasswordFails(): void
    {
        $contents = $this->post(
            'q=reset',
            [
                'csrf' => $this->session->get('csrf'),
            //                'email' => 'nonexist@useremail.com',
            ]
        );
        $this->assertStringContains('Reset password request failed', $contents);
        
        $contents = $this->post(
            'q=reset',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => 'nonexist@useremail.com',
            ]
        );
        $this->assertStringContains('Email address not found', $contents);
    }
    
    /**
     * Method canSeeResetPasswordWorks
     *
     * @return void
     */
    protected function canSeeResetPasswordWorks(): void
    {
        $contents = $this->post(
            'q=reset',
            [
                'csrf' => $this->session->get('csrf'),
                'email' => self::EMAIL,
            ]
        );
        $this->assertStringContains('email sent', $contents);
    }
    
    /**
     * Method canSeeNewPasswordFails
     *
     * @return void
     */
    protected function canSeeNewPasswordFails(): void
    {
        $contents = $this->get('q=reset&token=wron-token');
        $this->assertStringContains('Invalid token', $contents);
        
        $user = $this->crud->getRow(
            'user',
            ['token'],
            ['email' => self::EMAIL]
        );
        $token = $user['token'] ?? '';
        $contents = $this->post(
            'q=change&token=' . $token,
            [
                'csrf' => $this->session->get('csrf'),
            //                'password' => '',
            //                'password_retype' => '',
            ]
        );
        $this->assertStringContains('Password change failed', $contents);
        $this->assertStringContains('Mandatory, Invalid password', $contents);
        
        $contents = $this->post(
            'q=change&token=' . $token,
            [
                'csrf' => $this->session->get('csrf'),
                'password' => '',
            //                'password_retype' => '',
            ]
        );
        $this->assertStringContains('Password change failed', $contents);
        $this->assertStringContains('Mandatory, Invalid password', $contents);
        
        $contents = $this->post(
            'q=change&token=' . $token,
            [
                'csrf' => $this->session->get('csrf'),
                'password' => 'short',
                'password_retype' => '',
            ]
        );
        $this->assertStringContains('Password change failed', $contents);
        $this->assertStringContains('Invalid password', $contents);
        $this->assertStringContains("Doesn't match", $contents);
        
        $contents = $this->post(
            'q=change&token=' . $token,
            [
                'csrf' => $this->session->get('csrf'),
                'password' => 'longwithoutnumbers',
                'password_retype' => '',
            ]
        );
        $this->assertStringContains('Password change failed', $contents);
        $this->assertStringContains('Invalid password', $contents);
        $this->assertStringContains("Doesn't match", $contents);
        
        $contents = $this->post(
            'q=change&token=' . $token,
            [
                'csrf' => $this->session->get('csrf'),
                'password' => 'withoutspecchar1234',
                'password_retype' => '',
            ]
        );
        $this->assertStringContains('Password change failed', $contents);
        $this->assertStringContains('Invalid password', $contents);
        $this->assertStringContains("Doesn't match", $contents);
    }
    
    /**
     * Method canSeeNewPassword
     *
     * @return void
     */
    protected function canSeeNewPassword(): void
    {
        $user = $this->crud->getRow(
            'user',
            ['token'],
            ['email' => self::EMAIL]
        );
        $contents = $this->get('q=reset&token=' . ($user['token'] ?? ''));
        $this->assertStringContains('Change password', $contents);
        // TODO check if correct form exists
    }
    
    /**
     * Method canSeeNewPasswordWorks
     *
     * @return void
     */
    protected function canSeeNewPasswordWorks(): void
    {
        $user = $this->crud->getRow(
            'user',
            ['token'],
            ['email' => self::EMAIL]
        );
        $contents = $this->post(
            'q=change&token=' . ($user['token'] ?? ''),
            [
                'csrf' => $this->session->get('csrf'),
                'password' => self::PASSWORD,
                'password_retype' => self::PASSWORD,
            ]
        );
        $this->assertStringContains('Password is changed', $contents);
    }
    
    /**
     * Method getLastEmail
     *
     * @return SplFileInfo
     * @throws RuntimeException
     */
    protected function getLastEmail(): SplFileInfo
    {
        //        $dir = realpath($folder);
        //        if (false === $dir) {
        //            throw new RuntimeException('Folder not exists: ' . $folder);
        //        }
        $folder = $this->config->get(Mailer::CONFIG_SECION)->get('save_mail_path');
        $mails = $this->folders->getFilesRecursive($folder);
        $latest = null;
        foreach ($mails as $mail) {
            if ($mail->isDir()) {
                continue;
            }
            if (!$latest) {
                $latest = $mail;
                continue;
            }
            if ($latest->getMTime() < $mail->getMTime()) {
                $latest = $mail;
            }
        }
        if (!$latest) {
            throw new RuntimeException(
                'Mail file is not found in folder: ' . $folder
            );
        }
        return $latest;
    }
    
    /**
     * Method getLastEmailFilename
     *
     * @return string
     */
    protected function getLastEmailFilename(): string
    {
        $lastEmail = $this->getLastEmail();
        return $lastEmail->getPathname(); // $lastEmail->getFilename();
    }
    
    /**
     * Method getLastEmailContents
     *
     * @return string
     * @throws RuntimeException
     *
     * @suppress PhanUnreferencedProtectedMethod
     */
    protected function getLastEmailContents(): string
    {
        $mailfile = $this->getLastEmailFilename();
        $contents = file_get_contents($mailfile);
        if (false === $contents) {
            throw new RuntimeException('Unable to read: ' . $mailfile);
        }
        return $contents;
    }
}
