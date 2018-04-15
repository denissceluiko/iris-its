<?php

namespace App\Mattermost;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * @var array
     */
    protected $actions = [];
    /**
     * @var array
     */
    protected $fields = [];
    /**
     * @var array
     */
    protected $payload = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);


    }

    /**
     * Add an action button.
     *
     * @param string $name
     * @param array $context
     * @param string $url
     * @return Attachment
     */
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

    /**
     * Add an author.
     *
     * @param string $name
     * @param string|null $icon
     * @param string|null $link
     * @return Attachment
     */
    public function author($name, $icon = null, $link = null)
    {
        $this->payload['author_name'] = $name;
        if ($icon) $this->payload['author_icon'] = $icon;
        if ($icon) $this->payload['author_link'] = $link;
        return $this;
    }

    /**
     * Set color of the left border
     * @param string $code
     * @return Attachment
     */
    public function color($code)
    {
        $this->payload['color'] = $code;
        return $this;
    }

    /**
     * Returns attachment as an Mattermost ready array.
     *
     * @return array
     */
    public function getPayload()
    {
        $output = $this->payload;
        if (!empty($this->actions)) $output['actions'] = $this->actions;
        if (!empty($this->fields)) $output['fields'] = $this->fields;
        return $output;
    }

    /**
     * Add a fallback text.
     *
     * @param string $value
     * @return Attachment
     */
    public function fallback($value)
    {
        $this->payload['fallback'] = $value;
        return $this;
    }

    /**
     * Adds a field to the attachment.
     *
     * @param string $title
     * @param string $value
     * @param bool $isShort
     * @return Attachment
     */
    public function field($title, $value, $isShort = false)
    {
        $field = [];
        $field['title'] = $title;
        $field['value'] = $value;
        $field['short'] = $isShort;
        $this->fields[] = $field;
        return $this;
    }

    /**
     * Add an image to the attachment.
     *
     * @param string $link
     * @return Attachment
     */
    public function image($link)
    {
        $this->payload['image_url'] = $link;
        return $this;
    }

    /**
     * Add text for the attachment.
     *
     * @param string $value
     * @return Attachment
     */
    public function text($value)
    {
        $this->payload['text'] = $value;
        return $this;
    }

    /**
     * Add text that comes before the attachment.
     *
     * @param string $value
     * @return Attachment
     */
    public function pretext($value)
    {
        $this->payload['pretext'] = $value;
        return $this;
    }

    /**
     * Add title for the attachment.
     *
     * @param string $title
     * @param string|null $link
     * @return Attachment
     */
    public function title($title, $link = null)
    {
        $this->payload['title'] = $title;
        if ($link) $this->payload['title_link'] = $link;
        return $this;
    }
}
