error_exit() {
    echo ""
    echo "TESTS FAILED"
    exit 1
}

success_exit() {
    echo ""
    echo "TESTS PASSED"
    exit 0
}

show_next() {
    echo ""
    echo "====================================================================="
    echo "-- [ $1 ]"
    echo "====================================================================="
    echo ""
}

trap error_exit 0 

show_next "clean up.."
echo "" > route.cache.php

show_next "php-cs-fixer"
vendor/bin/php-cs-fixer fix lib
vendor/bin/php-cs-fixer fix src

show_next "csfix.php"
php lib/tools/csfix/csfix.php lib
php lib/tools/csfix/csfix.php src

show_next "phpcbf"
vendor/bin/phpcbf lib
vendor/bin/phpcbf src

set -e

show_next "phpcs"
vendor/bin/phpcs lib --ignore=*/tools/*,*.cache.php
vendor/bin/phpcs src

show_next "phpstan"
vendor/bin/phpstan analyse --level 8 lib
vendor/bin/phpstan analyse --level 8 src

show_next "phpmd"
vendor/bin/phpmd lib text cleancode,codesize,controversial,design,naming,unusedcode --exclude tools/*
vendor/bin/phpmd src text cleancode,codesize,controversial,design,naming,unusedcode

show_next "phan"
vendor/bin/phan

show_next "test.php"
php lib/tools/test/test.php

trap success_exit 0