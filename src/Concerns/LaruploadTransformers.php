<?php

namespace Mostafaznv\Larupload\Concerns;

use Mostafaznv\Larupload\Enums\LaruploadMode;
use Mostafaznv\Larupload\Storage\Proxy\AttachmentProxy;
use stdClass;
use Mostafaznv\Larupload\Storage\Attachment;

trait LaruploadTransformers
{
    /**
     * Handle the dynamic setting of attachment objects
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value): void
    {
        if ($attachment = $this->getAttachment($key)) {
            $attachment->attach($value);
        }
        else {
            parent::setAttribute($key, $value);
        }
    }

    /**
     * Handle the dynamic retrieval of attachment objects
     *
     * @param string $key
     * @return mixed|null
     */
    public function getAttribute($key): mixed
    {
        if ($attachment = $this->getAttachment($key)) {
            return new AttachmentProxy($attachment);
        }

        return parent::getAttribute($key);
    }

    /**
     * Get All styles (original, cover and ...) of entities for this model
     *
     * @param string|null $name
     * @return object|null
     */
    public function getAttachments(string $name = null): object|null
    {
        if ($name) {
            if ($attachment = $this->getAttachment($name)) {
                return $attachment->urls();
            }

            return null;
        }
        else {
            $attachments = new stdClass();

            foreach ($this->attachments as $attachment) {
                $attachments->{$attachment->getName()} = $attachment->urls();
            }

            return $attachments;
        }
    }

    /**
     * @param string $name
     * @return AttachmentProxy|null
     */
    public function attachment(string $name): ?AttachmentProxy
    {
        if ($attachment = $this->getAttachment($name)) {
            return new AttachmentProxy($attachment);
        }

        return null;
    }

    /**
     * Override toArray method
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = $this->hideLaruploadColumns(parent::toArray());

        // attach attachment entities to array/json response
        foreach ($this->getAttachments() as $name => $attachment) {
            $array[$name] = $attachment;
        }

        return $array;
    }

    /**
     * Retrieve attachment if exists, otherwise return null
     *
     * @param $name
     * @return Attachment|null
     */
    private function getAttachment($name): ?Attachment
    {
        foreach ($this->attachments as $attachment) {
            if ($attachment->getName() == $name) {
                return $attachment;
            }
        }

        return null;
    }

    /**
     * Hide larupload columns from toArray function
     *
     * @param array $array
     * @return array
     */
    private function hideLaruploadColumns(array $array): array
    {
        if ($this->hideLaruploadColumns) {
            foreach ($this->attachments as $attachment) {
                $name = $attachment->getName();

                unset($array["{$name}_file_name"]);

                if ($attachment->getMode() === LaruploadMode::HEAVY) {
                    unset($array["{$name}_file_id"]);
                    unset($array["{$name}_file_original_name"]);
                    unset($array["{$name}_file_size"]);
                    unset($array["{$name}_file_type"]);
                    unset($array["{$name}_file_mime_type"]);
                    unset($array["{$name}_file_width"]);
                    unset($array["{$name}_file_height"]);
                    unset($array["{$name}_file_duration"]);
                    unset($array["{$name}_file_dominant_color"]);
                    unset($array["{$name}_file_format"]);
                    unset($array["{$name}_file_cover"]);
                }
                else {
                    unset($array["{$name}_file_meta"]);
                }
            }
        }

        return $array;
    }
}
