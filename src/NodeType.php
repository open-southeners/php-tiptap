<?php

namespace OpenSoutheners\Tiptap;

enum NodeType: string
{
    case Blockquote = 'blockquote';

    case BulletList = 'bulletList';

    case CodeBlock = 'codeBlock';

    case CodeBlockHighlight = 'codeBlockHighlight';

    case CodeBlockShiki = 'codeBlockShiki';

    case Document = 'doc';

    case HardBreak = 'hardBreak';

    case Heading = 'heading';

    case HorizontalRule = 'horizontalRule';

    case Image = 'image';

    case ListItem = 'listItem';

    case Mention = 'mention';

    case OrderedList = 'orderedList';

    case Paragraph = 'paragraph';

    case Table = 'table';

    case TableCell = 'tableCell';

    case TableHeader = 'tableHeader';

    case TableRow = 'tableRow';

    case TaskItem = 'taskItem';

    case TaskList = 'taskList';

    case Text = 'text';
}
