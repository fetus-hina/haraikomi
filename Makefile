RESOURCES := \
	web/css/site.css \
	web/js/gienkin.js \
	web/js/messagebox.js \
	web/js/polyfill.js \
	web/js/postalcode.js \
	web/js/save.js

.PHONY: all
all: vendor app-config resources

.PHONY: clean
clean:
	rm -rf composer.phar node_modules vendor web/css/*.css web/js/*.js

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
	npx eslint "./resources/**/*.js"

check-style-css: node_modules
	npx stylelint "./resources/**/*.scss"

vendor: composer.lock composer.phar
	./composer.phar install

composer.phar:
	curl -fsSL 'https://getcomposer.org/installer' -- | php -- --stable
	touch -r composer.json composer.phar

config/cookie.php: vendor
	./yii app-config/cookie > runtime/.config.cookie.php
	cp runtime/.config.cookie.php $@
	rm -f runtime/.config.cookie.php

.PHONY: resources
resources: $(RESOURCES)

node_modules: package-lock.json
	npm ci
	@touch $@

package-lock.json: package.json
	@rm -rf $@ node_modules
	npm update
	@touch $@

web/css/%.css: resources/css/%.scss node_modules
	npx sass $< \
		| npx postcss --no-map --use autoprefixer \
		| npx cleancss --output $@ -O 1 --format "breaks:afterRuleEnds=on"

web/js/%.js: resources/js/%.js node_modules
	npx babel -s false $< | npx uglifyjs -c -m -b beautify=false,ascii_only=true --comments '/license|copyright/i' -o $@
