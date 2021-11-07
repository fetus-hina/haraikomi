RESOURCES := \
	web/css/site.css \
	web/js/gienkin.js \
	web/js/messagebox.js \
	web/js/polyfill.js \
	web/js/postalcode.js \
	web/js/save.js

.PHONY: all
all: .browserslistrc vendor app-config resources

.PHONY: clean
clean:
	rm -rf .browserslistrc composer.phar node_modules vendor web/css/*.css web/js/*.js

.PHONY: app-config
app-config: config/cookie.php

.PHONY: check-style
check-style: check-style-php check-style-js check-style-css

.PHONY: check-style-php
check-style-php: check-style-phpcs check-style-phpstan

.PHONY: check-style-phpcs
check-style-phpcs: vendor
	vendor/bin/phpcs

.PHONY: check-style-phpstan
check-style-phpstan: vendor
	vendor/bin/phpstan analyze --memory-limit=1G || true

check-style-js: node_modules
	npx semistandard --global=jQuery --global=bootstrap "./resources/**/*.js"

check-style-css: node_modules
	npx stylelint "./resources/**/*.scss"

vendor: composer.lock composer.phar
	./composer.phar install --prefer-dist
	@touch $@

composer.phar:
	curl -fsSL 'https://getcomposer.org/installer' -- | php -- --stable
	@touch $@

config/cookie.php: vendor
	./yii app-config/cookie > runtime/.config.cookie.php
	cp runtime/.config.cookie.php $@
	rm -f runtime/.config.cookie.php

.PHONY: resources
resources: $(RESOURCES)

node_modules: package-lock.json
	npm clean-install
	@touch $@

web/css/%.css: resources/css/%.scss node_modules .browserslistrc
	npx sass $< | npx postcss --no-map --use autoprefixer --use cssnano --output=$@

web/js/%.js: resources/js/%.js node_modules .browserslistrc
	npx babel -s false $< | npx terser -c -m -f ascii_only=true --comments '/license|copyright/i' -o $@

.PHONY: test
test: composer.phar app-config vendor node_modules resources
	@rm -f runtime/test-db.sqlite
	./tests/bin/yii migrate/up --interactive=0 --compact=1
	/usr/bin/env XDEBUG_MODE=coverage \
		./vendor/bin/codecept run unit \
			--skip-group large \
			--coverage \
			--coverage-html=./web/coverage/ \
			--coverage-text=./runtime/coverage/coverage.txt \
			--coverage-xml=./runtime/coverage/coverage.xml
.PHONY: full-test
full-test: composer.phar app-config vendor node_modules resources
	@rm -f runtime/test-db.sqlite
	./tests/bin/yii migrate/up --interactive=0 --compact=1
	/usr/bin/env XDEBUG_MODE=coverage \
		./vendor/bin/codecept run unit \
			--coverage \
			--coverage-html=./web/coverage/ \
			--coverage-text=./runtime/coverage/coverage.txt \
			--coverage-xml=./runtime/coverage/coverage.xml

.browserslistrc:
	curl -fsSL -o $@ 'https://raw.githubusercontent.com/twbs/bootstrap/v5.0.2/.browserslistrc'

bin/dep:
	curl -fsSL -o $@ 'https://deployer.org/releases/v6.8.0/deployer.phar'
	chmod +x $@
