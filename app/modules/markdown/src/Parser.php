<?php

namespace Pagekit\Markdown;

use Pagekit\Markdown\Lexer\InlineLexer;

class Parser
{
    protected $token;
    protected ?array $tokens = null;
    protected ?InlineLexer $inline = null;
    protected $renderer;
    protected array $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
        $this->renderer = $options['renderer'];
    }

    /**
     * Compiling method.
     *
     * @param  array $src
     */
    public function parse($src): string
    {
        $this->inline = new InlineLexer($src['links'], $this->options);

        unset($src['links']);

        $this->tokens = array_reverse($src);

        $out = '';

        while ($this->next()) {
            $out .= $this->tok();
        }

        return $out;
    }

    /**
     * Next token.
     */
    protected function next(): ?array
    {
        return $this->token = array_pop($this->tokens);
    }

    /**
     * Preview next token.
     */
    protected function peek(): array
    {
        return end($this->tokens);
    }

    /**
     * Parse text tokens.
     */
    protected function parseText(): string
    {
        $body = $this->token['text'];

        while (($token = $this->peek()) && $token['type'] == 'text') {
          $body .= "\n".$token['text'];
          $this->next();
        }

        return $this->inline->output($body);
    }

    /**
     * Parse current token.
     *
     * @return string
     */
    protected function tok()
    {
        $body = '';

        switch ($this->token['type']) {

            case 'space':

                return '';

            case 'hr':

                return $this->renderer->hr();

            case 'heading':

                return $this->renderer->heading($this->inline->output($this->token['text']), $this->token['depth'], $this->token['text']);

            case 'code':

                return $this->renderer->code($this->token['text'], @$this->token['lang'], @$this->token['escaped']);

            case 'table':

                $header = '';
                $cell   = '';
                $itemsCount = count($this->token['header']);

                for ($i = 0; $i < $itemsCount; $i++) {
                    $flags = ['header' => true, 'align' => $this->token['align'][$i]];
                    $cell .= $this->renderer->tablecell($this->inline->output($this->token['header'][$i]), $flags);
                }

                $header .= $this->renderer->tablerow($cell);
                $itemsCount = count($this->token['cells']);

                for ($i = 0; $i < $itemsCount; $i++) {

                    $row  = $this->token['cells'][$i];
                    $cell = '';

                    foreach ($row as $j => $row) {
                        $flags = ['header' => false, 'align' => $this->token['align'][$j]];
                        $cell .= $this->renderer->tablecell($this->inline->output($row), $flags);
                    }

                    $body .= $this->renderer->tablerow($cell);
                }

                return $this->renderer->table($header, $body);

            case 'blockquote_start':

                while ($this->next() && $this->token['type'] !== 'blockquote_end') {
                    $body .= $this->tok();
                }

                return $this->renderer->blockquote($body);

            case 'list_start':

                $ordered = $this->token['ordered'];

                while ($this->next() && $this->token['type'] !== 'list_end') {
                    $body .= $this->tok();
                }

                return $this->renderer->lst($body, $ordered);

            case 'list_item_start':

                while ($this->next() && $this->token['type'] !== 'list_item_end') {
                    $body .= ($this->token['type'] === 'text') ? $this->parseText() : $this->tok();
                }

                return $this->renderer->listitem($body);

            case 'loose_item_start':

                while ($this->next() && $this->token['type'] !== 'list_item_end') {
                    $body .= $this->tok();
                }

                return $this->renderer->listitem($body);

            case 'html':

                $html = (!$this->token['pre'] && !$this->options['pedantic']) ? $this->inline->output($this->token['text']) : $this->token['text'];

                return $this->renderer->html($html);

            case 'paragraph':

                return $this->renderer->paragraph($this->inline->output($this->token['text']));

            case 'text':

                return $this->renderer->paragraph($this->parseText());
        }
    }
}
