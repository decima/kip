<?php


namespace App\Services;


class ParseDownImproved extends \Parsedown
{

    protected $newtablink = true;

    public function setAllLinksNewTab(bool $b = true)
    {
        $this->newtablink = $b === true;
    }

    protected function applyOwnLinkStuff(&$link)
    {
        // **snipp**
        if ($this->newtablink === true) {
            $link['target'] = "_blank";
        }
        // **snipp**
    }

    // overwritten methods from parsedown
    protected function inlineLink($Excerpt)
    {
        $temp = parent::inlineLink($Excerpt);
        if (is_array($temp)) {
            if (isset($temp['element']['attributes']['href'])) {
                $this->applyOwnLinkStuff($temp['element']['attributes']);
            }
            return $temp;
        }
    }

    protected function inlineUrl($Excerpt)
    {
        $temp = parent::inlineUrl($Excerpt);
        if (is_array($temp)) {
            if (isset($temp['element']['attributes']['href'])) {
                $this->applyOwnLinkStuff($temp['element']['attributes']);
            }
            return $temp;
        }
    }

    protected function fetchText($Text)
    {
        return trim(strip_tags($this->line($Text)));
    }

    protected function createAnchorID($Text)
    {
        return urlencode($this->fetchText($Text)) . "+" . substr(md5(uniqid()), 0, 8);
    }

    #
    # contents list
    #
    public function contentsList()
    {
        return $this->contentsListArray;

    }

    #
    # Setters
    #
    protected function setContentsList($Content)
    {
        $this->setContentsListAsArray($Content);
        $this->setContentsListAsString($Content);
    }

    protected function setContentsListAsArray($Content)
    {
        $this->contentsListArray[] = $Content;
    }

    protected $contentsListArray = [];

    protected function setContentsListAsString($Content)
    {
        $text  = $this->fetchText($Content['text']);
        $id    = $Content['id'];
        $level = (integer)trim($Content['level'], 'h');
        $link  = "[${text}](#${id})";

        if ($this->firstHeadLevel === 0) {
            $this->firstHeadLevel = $level;
        }
        $cutIndent = $this->firstHeadLevel - 1;
        if ($cutIndent > $level) {
            $level = 1;
        } else {
            $level = $level - $cutIndent;
        }

        $indent = str_repeat('  ', $level);

        $this->contentsListString .= "${indent}- ${link}\n";
    }

    protected $contentsListString = '';
    protected $firstHeadLevel = 0;

    #
    # Header
    #
    protected function blockHeader($Line)
    {
        if (isset($Line['text'][1])) {
            $Block = \Parsedown::blockHeader($Line);

            // Compatibility with old Parsedown Version
            if (isset($Block['element']['handler']['argument'])) {
                $text = $Block['element']['handler']['argument'];
            }

            if (isset($Block['element']['text'])) {
                $text = $Block['element']['text'];
            }

            $level = $Block['element']['name'];    //levels are h1, h2, ..., h6
            $level = ltrim($level, "h");


            $id = $this->createAnchorID($text);

            //Set attributes to head tags
            $Block['element']['attributes'] = [
                'id'    => $id,
                'name'  => $id,
                "class" => "title",
            ];

            $this->setContentsList([
                'text'  => $text,
                'id'    => $id,
                'level' => $level,
            ]);

            return $Block;
        }
    }

    protected function inlineImage($Excerpt)
    {
        if (!isset($Excerpt['text'][1]) or $Excerpt['text'][1] !== '[') {
            return;
        }

        $Excerpt['text'] = substr($Excerpt['text'], 1);

        $Link = $this->inlineLink($Excerpt);

        if ($Link === null) {
            return;
        }

        $Inline = [
            'extent'  => $Link['extent'] + 1,
            'element' => [
                'name'       => 'img',
                'attributes' => [
                    'uk-img' => "",
                    'src'    => $Link['element']['attributes']['href'],
                    'alt'    => $Link['element']['text'],
                ],
            ],
        ];

        $Inline['element']['attributes'] += $Link['element']['attributes'];

        unset($Inline['element']['attributes']['href']);

        return $Inline;
    }
}