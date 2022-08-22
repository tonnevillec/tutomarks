ROUGE:=\033[1;31m
JAUNE:=\033[1;33m
VERT:=\033[1;32m
NORMAL:=\033[0;39m

# Version PHP
PHP_VERSION   = /usr/bin/php8.1

# Executables:
PHPUNIT       = ./vendor/bin/phpunit
PHPSTAN       = ./vendor/bin/phpstan
PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer
NPX           = npx
YARN          = yarn
COMPOSER      = /usr/bin/composer

usage:
	@echo "$(VERT)make cc                   : $(NORMAL)Effacer le cache avec la command >php bin/console c:c"
	@echo "$(VERT)make scc                  : $(NORMAL)Effacer le cache avec la commande >symfony console c:c"
	@echo "$(JAUNE)make docker-start        : $(NORMAL)Lance le-s conteneur-s Docker >sudo docker-compose up -d"
	@echo "$(JAUNE)make docker-stop         : $(NORMAL)Stop le-s conteneur-s Docker >sudo docker-compose stop"
	@echo "$(JAUNE)make sf-start            : $(NORMAL)Lance le serveur Symfony >sudo symfony server:start -d"
	@echo "$(JAUNE)make sf-stop             : $(NORMAL)Stop le serveur Symfony >sudo symfony server:stop -d"
	@echo "$(JAUNE)make all-start           : $(NORMAL)Lance le-s conteneur-s Docker et le serveur Symfony"
	@echo "$(JAUNE)make all-stop            : $(NORMAL)Stop le-s conteneur-s Docker et le serveur Symfony"

cc:
    $(PHP_VERSION) bin/console c:c

scc:
    symfony console c:c

# COMMANDES COMPOSER
composer-install:
	$(PHP_VERSION) $(COMPOSER) install --optimize-autoloader --no-interaction --no-progress

composer-update:
	$(PHP_VERSION) $(COMPOSER) update

composer-dump-env-dev:
	$(PHP_VERSION) $(COMPOSER) dump-env dev

# DOCKER
docker-start:
	docker-compose up -d

docker-stop:
	docker-compose stop

# docker-status:
#    sudo docker-compose ps

# SERVEUR SYMFONY
sf-start:
	symfony server:start -d

sf-stop:
	symfony server:stop

# sf-status:
#     sudo symfony server:status

# TESTS
start-test:
	$(PHP_VERSION) bin/phpunit --testdox

code-coverage:
	$(PHP_VERSION) bin/phpunit --coverage-html var/test/test-coverage

# COMMANDES DIVERS
# all-status: docker-status sf-status

all-start: docker-start sf-start

all-stop: docker-stop sf-stop

fixtures-test:
	$(PHP_VERSION) bin/console do:fi:lo -n --env=test

fixtures-dev:
	$(PHP_VERSION) bin/console do:fi:lo -n --env=dev

database-test: #Ne fonctionne pas avec une BDD sur sqlite
	$(PHP_VERSION) bin/console doctrine:database:drop --if-exists --force --env=test
	$(PHP_VERSION) bin/console doctrine:database:create --env=test
	$(PHP_VERSION) bin/console doctrine:schema:update --force --env=test

database-test-sqlite:
	$(PHP_VERSION) bin/console doctrine:schema:update --force --env=test

database-dev:
	$(PHP_VERSION) bin/console doctrine:database:drop --if-exists --force --env=dev
	$(PHP_VERSION) bin/console doctrine:database:create --env=dev
	$(PHP_VERSION) bin/console doctrine:schema:update --force --env=dev

prepare-test: #Ne fonctionne pas avec une BDD sur sqlite
	make database-test
	make fixtures-test

prepare-test-sqlite:
	make database-test-sqlite
	make fixtures-test

prepare-dev:
	make database-dev
	make fixtures-dev

fix:
	vendor/bin/phpcbf src

## —— Coding standards ✨ ——————————————————————————————————————————————————————
cs: lint-php lint-js ## Run all coding standards checks

static-analysis: stan ## Run the static analysis (PHPStan)

stan: ## Run PHPStan
	@$(PHPSTAN) analyse -c configuration/phpstan.neon --memory-limit 1G

lint-php: ## Lint files with php-cs-fixer
	/usr/bin/php8.1 ./vendor/bin/php-cs-fixer fix --allow-risky=yes --dry-run

fix-php: ## Fix files with php-cs-fixer
	/usr/bin/php8.1 ./vendor/bin/php-cs-fixer fix --allow-risky=yes

lint-js: ## Lints JS coding standards
	@$(NPX) eslint assets/js

fix-js: ## Fixes JS files
	@$(NPX) eslint assets/js --fix