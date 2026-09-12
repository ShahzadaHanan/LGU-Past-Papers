<?php

declare(strict_types=1);

namespace App\Services;

class SeoService
{
    private string $title='';

    private string $description='';

    private string $canonical='';

    private array $keywords=[];

    private array $openGraph=[];

    private string $ogImage='';

    private array $jsonLd=[];

    private string $robots='index, follow';

    public function title(
        string $title
    ):self{

        $this->title=$title;

        return $this;

    }

    public function description(
        string $description
    ):self{

        $this->description=$description;

        return $this;

    }

    public function canonical(
        string $url
    ):self{

        $this->canonical=$url;

        return $this;

    }

    public function keywords(
        array $keywords
    ):self{

        $this->keywords=$keywords;

        return $this;

    }

    public function openGraph(
        array $data
    ):self{

        $this->openGraph=$data;

        return $this;

    }

    public function ogImage(
        string $url
    ):self{

        $this->ogImage=$url;

        return $this;

    }

    /** Accepts one schema.org node, or an array of nodes ('@graph'). */
    public function jsonLd(
        array $schema
    ):self{

        $this->jsonLd[]=$schema;

        return $this;

    }

    public function robots(
        string $directive
    ):self{

        $this->robots=$directive;

        return $this;

    }

    public function data():array
    {
        return[
            'title'=>$this->title,
            'description'=>$this->description,
            'canonical'=>$this->canonical,
            'keywords'=>implode(',',$this->keywords),
            'og'=>$this->openGraph,
            'ogImage'=>$this->ogImage,
            'jsonLd'=>$this->jsonLd,
            'robots'=>$this->robots,
        ];
    }
}