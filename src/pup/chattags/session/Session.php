<?php
namespace pup\chattags\session;

final class Session
{
    private string $activeTag = '';

    public function __construct(private string $xuid, private array $data){}

    public function getXuid(): string {
        return $this->xuid;
    }

    public function getData(): array {
        return [
            'tags' => $this->data,
            'activeTag' => $this->activeTag
        ];
    }

    public function updateData(string $tag): void {
        if (!in_array($tag, $this->data)) {
            $this->data[] = $tag;
        }
    }

    public function getActiveTag(): string {
        return $this->activeTag;
    }

    public function setActiveTag(string $tag): void {
        $this->activeTag = $tag;
    }
}