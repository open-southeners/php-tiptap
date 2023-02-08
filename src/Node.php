<?php

namespace OpenSoutheners\Tiptap;

use Exception;
use function OpenSoutheners\LaravelHelpers\Strings\is_json;
use Throwable;

class Node implements \Iterator
{
    public function __construct(
        protected NodeType|string $type = NodeType::Paragraph,
        protected array $content = [],
        protected array $attributes = [],
        private int $position = 0
    ) {
        //
    }

    public static function fromString(string $value): static
    {
        if (! is_json($value)) {
            return TextNode::make($value);
        }

        $content = [];

        try {
            $content = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            throw new Exception('Parsing error');
        }

        return static::fromArray($content);
    }

    public function toJson(): string
    {
        $nodeStr = json_encode($this->toArray());

        if (! is_string($nodeStr)) {
            throw new Exception('Error stringifying node.');
        }

        return $nodeStr;
    }

    public static function fromArray(array $value): static
    {
        $content = $value['content'] ?? $value['text'] ?? [];

        if (is_array($content) && is_array(reset($content))) {
            for ($i = 0; $i < count($content); $i++) {
                $content[$i] = static::fromArray($content[$i]);
            }
        }

        if (is_string($content)) {
            $content = ['text' => $content];
        }

        return new static(
            NodeType::tryFrom($value['type']) ?? NodeType::Paragraph,
            $content,
            $value['attrs'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'attrs' => $this->attributes,
            $this->getTextContentKey() => $this->content,
        ];
    }

    protected function getTextContentKey(): string
    {
        return $this->isType(NodeType::Text) ? 'text' : 'content';
    }

    public function isType(NodeType|string $type): bool
    {
        return $this->type === $type;
    }

    /**
     * Get content node type.
     */
    public function type(): NodeType|string
    {
        return $this->type;
    }

    /**
     * Get node content.
     */
    public function content(): array
    {
        return $this->content;
    }

    /**
     * Get node content as raw text.
     */
    public function textContent(): string
    {
        if ($this->isType(NodeType::Text)) {
            return $this->content['text'];
        }

        $textContent = '';

        foreach ($this->content as $contentNode) {
            $textContent .= $contentNode->textContent();
        }

        return $textContent;
    }

    /**
     * Get node attributes.
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get first content node children.
     */
    public function firstChild(): static|null
    {
        return reset($this->content) ?: null;
    }

    /**
     * Get last content node children.
     */
    public function lastChild(): static|null
    {
        return end($this->content) ?: null;
    }

    public function setContent(Node|array|string $content): static
    {
        if (is_a($content, Node::class)) {
            $this->content = $content;
        }

        if (is_array($content)) {
            foreach ($content as $contentItem) {
                $this->content[] = Node::fromArray($contentItem);
            }
        }

        if (is_string($content) && $this->firstChild()->type() === NodeType::Text) {
            $this->firstChild()->setContent($content);
        }

        return $this;
    }

    /**
     * Returns the current element.
     */
    public function current(): static
    {
        return $this->content[$this->position];
    }

    /**
     * Returns the key of the current element.
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * Move forward to next element.
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * Rewind the Iterator to the first element.
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Checks if current position is valid.
     */
    public function valid(): bool
    {
        return isset($this->content[$this->position]);
    }
}
