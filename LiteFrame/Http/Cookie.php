<?php

namespace LiteFrame\Http;

class Cookie {

    private string $name;
    protected string $value;

    protected array $attributes;

    function __construct(string $name, string $value, array $attrs = []) {
        $this->name = $name;
        $this->value = $value;
        $this->attributes = $attrs;
    }

    public function name(): string {
        return $this->name;
    }

    public function value(): string {
        return $this->value;
    }

    public function setValue(string $value): void {
        $this->value = $value;
    }


    public function setAllAttributes(array $attrs): void {
        $this->attributes = $attrs;
    }

    public function setAttribute(string $attr, string|null $val = null) {
        $this->attributes[$attr] = $val;
    }

    public function attribute(string $attr): string|null {
        return $this->attributes[$attr] ?? null;
    }

    public function getAllAttributes(): array {
        return $this->attributes;
    }

    public function removeAttribute(string $attr): void {
        unset($this->attributes[$attr]);
    }

    public function hasAttribute(string $attr): bool {
        return array_key_exists($attr, $this->attributes);
    }

    public function toHeaderString(): string {
        $str =  urlencode($this->name)."=".urlencode($this->value);

        $attrs = $this->getAllAttributes();
        if (sizeof($attrs) > 0) {
            $str .= ";";
            foreach ($attrs as $name => $value) {
                $str .= " ".urlencode($name);
                if (isset($value) && !empty($value)) {
                    $str .= "=".urlencode($value);
                }
                $str .= ";";
            }
        }

        return $str;
    }

}


?>