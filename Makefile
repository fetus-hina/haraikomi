RESOURCES := web/css/site.css

.PHONY: all
all: vendor app-config resources

.PHONY: clean
clean:
	rm -rf vendor composer.phar node_modules web/css/*.css web/js/*.js

.PHONY: app-config
app-config: config/cookie.php

vendor: composer.lock composer.phar
	./composer.phar install -vvv

composer.lock: composer.json composer.phar
	./composer.phar update -vvv
	touch -r composer.json composer.lock

composer.phar:
	curl 'https://getcomposer.org/installer' -- | php -- --stable
	./composer.phar global require 'fxp/composer-asset-plugin:^1.3'
	touch -r composer.json composer.phar

config/cookie.php: vendor
	./yii app-config/cookie > runtime/.config.cookie.php
	cp runtime/.config.cookie.php $@
	rm -f runtime/.config.cookie.php

.PHONY: resources
resources: $(RESOURCES)

BROWSERS_CONFIG := "> 2% in JP,last 2 versions,last 2 Chrome,Firefox ESR,Android>=4.4,iOS>=10"
RESOURCE_PRECOND := node_modules
NODE := node_modules/.bin

node_modules: package.json
	npm install
	touch -r package-lock.json node_modules

web/css/%.css: resources/css/%.scss $(RESOURCE_PRECOND)
	$(NODE)/node-sass -q -x $< \
		| $(NODE)/postcss --no-map \
			--use autoprefixer --autoprefixer.browsers $(BROWSERS_CONFIG) \
		| $(NODE)/cleancss --output $@ -O 1 --format "breaks:afterRuleEnds=on"

web/js/%.js: resources/js/%.es
	$(NODE)/babel -q $< \
		| $(NODE)/uglifyjs --compress --mangle --beautify ascii_only=true,beautify=false --output $@
