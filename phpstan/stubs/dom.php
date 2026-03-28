<?php

namespace Dom;

class Node
{
    /** @readonly */
    public string $textContent;

    /**
     * @readonly
     * @var NodeList<Node>
     */
    public NodeList $childNodes;
}

class Element extends Node
{
    /** @readonly */
    public string $localName;

    /** @readonly */
    public string $tagName;

    public function getAttribute(string $qualifiedName): ?string {}

    public function setAttribute(string $qualifiedName, string $value): void {}

    public function querySelector(string $selectors): ?Element {}

    /** @return NodeList<Element> */
    public function querySelectorAll(string $selectors): NodeList {}
}

class HTMLDocument extends Node
{
    public static function createFromString(string $source, int $options = 0, ?string $overrideEncoding = null): HTMLDocument {}

    public static function createFromFile(string $path, int $options = 0, ?string $overrideEncoding = null): HTMLDocument {}

    public static function createEmpty(string $encoding = 'UTF-8'): HTMLDocument {}

    public function saveHtml(?Node $node = null): string {}

    public function querySelector(string $selectors): ?Element {}

    /** @return NodeList<Element> */
    public function querySelectorAll(string $selectors): NodeList {}
}

/**
 * @template TNode of Node
 * @implements \IteratorAggregate<int, TNode>
 */
class NodeList implements \IteratorAggregate, \Countable
{
    /** @readonly */
    public int $length;

    /** @return TNode|null */
    public function item(int $index): ?Node {}

    public function count(): int {}

    /** @return \Iterator<int, TNode> */
    public function getIterator(): \Iterator {}
}
