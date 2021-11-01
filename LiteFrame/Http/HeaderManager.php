<?php

namespace LiteFrame\Http;

class HeaderManager {

    /**
     * @var Header[] rawArray
     */
    protected array $rawArray;

    function __construct(array $raw = [], bool $isAssociative = true) {
        $this->rawArray = [];

        if ($isAssociative) {
            foreach ($raw as $name => $value) {
                $header = new Header($name, $value);
                array_push($this->rawArray, $header);
            }
        }
        else {
            foreach ($raw as $header) {
                array_push($this->rawArray, $header);
            }
        }
    }

    public function get(string $name): string|null {
        foreach ($this->rawArray as $header) {
            if ($header->is($name)) {
                return $header->value;
            }
        }
        return null;
    }


    /**
     * @return Header[] of raw cookie objects matching $name
     */
    public function getAllMatching(string $name): array {
        $result = [];
        foreach ($this->rawArray as $header) {
            if ($header->is($name)) {
                array_push($result, $header);
            }
        }
        return $result;
    }

    /**
     * @return Header A copy of the first matching header
     */
    public function getRaw(string $name): Header|null {
        $result = [];
        foreach ($this->rawArray as $header) {
            if ($header->is($name)) {
                return new Header($header->name(), $header->value);
            }
        }
        return $result;
    }


    /**
     * @return Header[]
     */
    public function getAllRaw(): array {
        return $this->rawArray;
    }

    public function set(string $name, string $value, bool $replaceExisting = true): void {
        $newArray = [];

        foreach ($this->rawArray as $header) {
            if ($header->is($name)) {
                if (!$replaceExisting) {
                    array_push($newArray, $header);
                }
            }
            else {
                array_push($newArray, $header);
            }
        }

        array_push($newArray, new Header($name, $value));
        $this->rawArray = $newArray;

    }

}

?>