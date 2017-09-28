.PHONY: all
all: vendor app-config

.PHONY: clean
clean:
	rm -rf vendor composer.phar

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
