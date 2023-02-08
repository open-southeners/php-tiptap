<?php

namespace OpenSoutheners\Tiptap;

class TextNode extends Node
{
    public static function make(string $text): static
    {
        return new static(NodeType::Text, ['text' => $text]);
    }
}
