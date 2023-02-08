<?php

namespace OpenSoutheners\Tiptap\Tests;

use OpenSoutheners\Tiptap\Node;
use OpenSoutheners\Tiptap\NodeType;
use OpenSoutheners\Tiptap\Tiptap;

class TiptapTest extends TestCase
{
    public function testTiptapFromJsonContent()
    {
        $tiptap = Tiptap::fromContent('{"type":"doc","content":[{"type":"paragraph","attrs":{"textAlign":"left"},"content":[{"text":"probably?","type":"text"}]}]}');

        $this->assertTrue($tiptap->getDocument() instanceof Node);
        $this->assertTrue($tiptap->getDocument()->type() instanceof NodeType);
        $this->assertTrue($tiptap->getDocument()->type() === NodeType::Document);
        $this->assertTrue($tiptap->getDocument()->firstChild() instanceof Node);
        $this->assertTrue($tiptap->getDocument()->firstChild()->type() === NodeType::Paragraph);
    }

    public function testTiptapFromJsonContentToRawText()
    {
        $tiptap = Tiptap::fromContent('{"type":"doc","content":[{"type":"paragraph","attrs":{"textAlign":"left"},"content":[{"text":"probably?","type":"text"}]}]}');

        $text = $tiptap->getDocument()->textContent();

        $this->assertIsString($text);
        $this->assertEquals('probably?', $text);
    }

    public function testTiptapFromJsonContentToRawTextHavingNestedNodes()
    {
        $tiptap = Tiptap::fromContent('{"type":"doc","content":[{"type":"heading","attrs":{"level":1,"textAlign":"left"},"content":[{"type":"glossaryItem","attrs":{"id":"4","type":"glossary","label":"MTE"}},{"text":" ","type":"text"}]},{"type":"paragraph","attrs":{"textAlign":"left"},"content":[{"text":"probably?","type":"text"}]}]}');

        $text = $tiptap->getDocument()->textContent();

        $this->assertIsString($text);
        $this->assertEquals('MTE probably?', $text);
    }
}
