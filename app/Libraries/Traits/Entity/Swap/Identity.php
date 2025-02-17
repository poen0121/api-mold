<?php

namespace App\Libraries\Traits\Entity\Swap;

trait Identity
{
    /**
     * Get the transform id prefix word A-Z.
     *
     * @return string
     */
    protected function getPrimaryTidPrefixWord()
    {
        return;
    }

    /**
     * Get the transform id suffix length.
     *
     * @return int
     */
    protected function getPrimaryTidSuffixLength(): int
    {
        return 6;
    }

    /**
     * Get the identifier that will be exchanged.
     *
     * @return mixed
     */
    public function getIdentityIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the id dependency transform id.
     *
     * @param int $value
     * @return string|null
     */
    public function asPrimaryTid(int $value): ?string
    {
        if (preg_match('/^[1-9]{1}[0-9]*$/', $value)) {
            $value = (string) $value;

            $suffixCode = str_pad(hexdec(hash('crc32b', $value)), $this->getPrimaryTidSuffixLength(), '0', STR_PAD_LEFT);

            $suffixCode = substr($suffixCode, -$this->getPrimaryTidSuffixLength());

            return strtoupper(preg_replace('/[^A-Z]/i', '', (string) $this->getPrimaryTidPrefixWord())) . $value . $suffixCode;
        }

        return null;
    }

    /**
      * Get the id list dependency transform id list.
      *
      * @param array $value
      * @return array
      */
    public function asPrimaryTids(array $value): array
    {
        return collect($value)->map(function ($item) {
            return $this->asPrimaryTid($item);
        })->reject(function ($item) {
            return !isset($item);
        })
        ->all();
    }

    /**
    * Get the attribute for transform id.
    *
    * @return string|null
    */
    public function getTidAttribute(): ?string
    {
        return $this->asPrimaryTid((int) $this->getIdentityIdentifier());
    }

    /**
     * Get the transform id dependency id.
     *
     * @param string $value
     * @return int|null
     */
    public function asPrimaryId(string $value): ?int
    {
        $prefixWord = strtoupper(preg_replace('/[^A-Z]/i', '', (string) $this->getPrimaryTidPrefixWord()));
        if (preg_match('/^' . $prefixWord . '[1-9]{1}[0-9]{' . $this->getPrimaryTidSuffixLength() . '}[0-9]*$/', $value)) {
            $id = substr(substr($value, 0, -$this->getPrimaryTidSuffixLength()), strlen($prefixWord));
            /* Verification calculation */
            if ($this->asPrimaryTid((int) $id) === $value) {
                return $id;
            }
        }

        return null;
    }

    /**
      * Get the transform id list dependency id list.
      *
      * @param array $value
      * @return array
      */
    public function asPrimaryIds(array $value): array
    {
        return collect($value)->map(function ($item) {
            return $this->asPrimaryId($item);
        })->reject(function ($item) {
            return !isset($item);
        })
        ->all();
    }
}
