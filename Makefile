RESOURCES := web/css/site.css web/js/save.js

.PHONY: all
all: vendor app-config resources

.PHONY: clean
clean:
	rm -rf vendor composer.phar node_modules web/css/*.css web/js/*.js

.PHONY: app-config
app-config: config/cookie.php

.PHONY: check-style
check-style: vendor
	./vendor/bin/phpcs -p --extensions=php --standard=PSR12 assets commands config controllers models web

vendor: composer.lock composer.phar
	./composer.phar install -vvv

composer.lock: composer.json composer.phar
	./composer.phar update -vvv
	touch -r composer.json composer.lock

composer.phar:
	curl 'https://getcomposer.org/installer' -- | php -- --stable
	touch -r composer.json composer.phar

config/cookie.php: vendor
	./yii app-config/cookie > runtime/.config.cookie.php
	cp runtime/.config.cookie.php $@
	rm -f runtime/.config.cookie.php

.PHONY: resources
resources: $(RESOURCES)

node_modules: package.json
	npm install
	touch -r package-lock.json node_modules

web/css/%.css: resources/css/%.scss node_modules
	npx node-sass -q -x $< \
		| npx postcss --no-map --use autoprefixer \
		| npx cleancss --output $@ -O 1 --format "breaks:afterRuleEnds=on"

web/js/%.js: resources/js/%.js node_modules
	npx babel -s false $< | npx uglifyjs -c -m -b beautify=false,ascii_only=true --comments '/license|copyright/i' -o $@
