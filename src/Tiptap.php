<?php

namespace OpenSoutheners\Tiptap;

use function OpenSoutheners\LaravelHelpers\Strings\is_json;

class Tiptap
{
    public function __construct(protected Node $document)
    {
        // 
    }

    public function getDocument(): Node
    {
        return $this->document;
    }

    public static function fromContent(array|string $content): static
    {
        $document = new Node(NodeType::Document);

        if (is_string($content) && is_json($content)) {
            $content = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        }

        $document->setContent($content['content'] ?? []);

        return new static($document);
    }
}
