<?php

namespace App\Mattermost;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $actions = [];
    protected $fields = [];
    protected $payload = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);


    }

    public function action($name, $context, $url)
    {
        $action = [];
        $action['name'] = $name;
        $action['integration'] = [];
        $action['integration']['url'] = $url;
        $action['integration']['context'] = $context;
        $this->actions[] = $action;
        return $this;
    }

    public function author($name, $icon = null, $link = null)
    {
        $this->payload['author_name'] = $name;
        if ($icon) $this->payload['author_icon'] = $icon;
        if ($icon) $this->payload['author_link'] = $link;
        return $this;
    }

    public function color($code)
    {
        $this->payload['color'] = $code;
    }
    public function getPayload()
    {
        $output = $this->payload;
        if (!empty($this->actions)) $output['actions'] = $this->actions;
        if (!empty($this->fields)) $output['fields'] = $this->fields;
        return $output;
    }

    public function fallback($value)
    {
        $this->payload['fallback'] = $value;
        return $this;
    }

    public function field($title, $value, $isShort = false)
    {
        $field = [];
        $field['title'] = $title;
        $field['value'] = $value;
        $field['short'] = $isShort;
        $this->fields[] = $field;
        return $this;
    }

    public function image($link)
    {
        $this->payload['image_url'] = $link;
        return $this;
    }

    public function text($value)
    {
        $this->payload['text'] = $value;
        return $this;
    }

    public function pretext($value)
    {
        $this->payload['pretext'] = $value;
        return $this;
    }

    public function title($title, $link = null)
    {
        $this->payload['title'] = $title;
        if ($link) $this->payload['title_link'] = $link;
        return $this;
    }
}
