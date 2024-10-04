gendiff:
	./bin/gendiff

install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

tests:
	composer ./vendor/bin/phpunit

test-coverage:
	./vendor/bin/phpunit --coverage-clover build/logs/clover.xml