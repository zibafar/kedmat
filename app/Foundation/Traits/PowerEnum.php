<?php

namespace App\Foundation\Traits;

use ErrorException;
use Illuminate\Support\Str;
use ReflectionEnum;

trait PowerEnum
{
    /**
     * Get the values of the cases.
     */
    public static function values(): array
    {
        return array_column(array: self::cases(), column_key: 'value');
    }

    /**
     * Get the names of the cases.
     */
    public static function names(): array
    {
        return array_column(array: self::cases(), column_key: 'name');
    }

    /**
     * Get the names and the values of the cases.
     */
    public static function list(): array
    {
        return array_combine(keys: self::names(), values: self::values());
    }

    /**
     * Check the given value equals the value of the case.
     */
    public function equals(mixed $value): bool
    {
        if (is_object(value: $value) && property_exists(object_or_class: $value, property: 'value')) {
            $value = $value->value;
        }

        return $this->value == $value;
    }

    /**
     * This is another name for the method "equals".
     */
    public function is(mixed $value): bool
    {
        return $this->equals(value: $value);
    }

    /**
     * Set the labels of all the cases.
     */
    public static function setLabels(): array
    {
        return [
        ];
    }

    /**
     * Return the label of the case.
     *
     * @throws ErrorException
     */
    public function label(): string
    {
        return self::getLabels()[$this->value];
    }

    /**
     * Get the labels of the cases.
     *
     * @throws ErrorException
     */
    public static function getLabels(): array
    {
        $labels = self::setLabels();
        $values = self::values();

        if (empty($labels)) {
            return array_combine(keys: $values, values: $values);
        }

        foreach (array_keys(array: $labels) as $value) {
            if (!in_array(needle: $value, haystack: $values)) {
                throw new ErrorException(message: "$value is an invalid value.");
            }
        }

        return $labels;
    }

    /**
     * Set the Samples of all the cases.
     */
    public static function setSamples(): array
    {
        return [
        ];
    }


    /**
     * Get the samples of the cases.
     *
     * @throws ErrorException
     */
    public static function getSamples(): array
    {
        $samples = self::setSamples();
        $values = self::values();

        if (empty($samples)) {
            return array_combine(keys: $values, values: $values);
        }

        foreach (array_keys(array: $samples) as $value) {
            if (!in_array(needle: $value, haystack: $values)) {
                throw new ErrorException(message: "$value is an invalid value.");
            }
        }

        return $samples;
    }


    /**
     * Return the sample of the case.
     *
     * @throws ErrorException
     */
    public function sample(): string|array
    {
        return self::getSamples()[$this->value];
    }

    //-------------Validation ------------------

    /**
     * Set the Validations of all the cases.
     */
    public static function setValidations(): array
    {
        return [
        ];
    }


    /**
     * Get the validations of the cases.
     *
     * @throws ErrorException
     */
    public static function getValidations(): array
    {
        $validations = self::setValidations();
        $values = self::values();
        // Define the  value
        $specificValue = self::getType();

// Define the size of the array
        $arraySize = count($values);

// Fill the array with the specific value
        $array = array_fill(0, $arraySize, $specificValue);

        if (empty($validations)) {
            return array_combine(keys: $values, values: $array);
        }

        foreach (array_keys(array: $validations) as $value) {
            if (!in_array(needle: $value, haystack: $values)) {
                throw new ErrorException(message: "$value is an invalid value.");
            }
        }

        return $validations;
    }


    /**
     * Return the validation of the case.
     *
     * @throws ErrorException
     */
    public function validation(): string|array
    {
        return self::getValidations()[$this->value];
    }


    /**
     * Get the case from the given name.
     *
     * @throws ErrorException
     */
    public static function fromName(string $name): self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new ErrorException(message: 'The given name does not exist.');
    }

    /**
     * Get the all data of the cases.
     *
     * @throws ErrorException
     */
    public static function getAllInfo(): array
    {
        $names = self::names();
        $labels = self::getLabels();
        $values = self::values();
        $samples = self::getSamples();
        $validations = self::getValidations();
        $result = [];
        foreach ($values as $key => $value) {
            $result[] = [
                "value" => $value/* __(Str::Title($value))*/,
                "label" => $labels[$value]/*__(Str::Title($labels[$value]))*/ ?? "",
                "sample" => $samples[$value] /*__(Str::Title($samples[$value]))*/ ?? "",
                "validation" => $validations[$value] ?? "",
                "name" => $names[$key]
            ];
        }
        return $result;
    }


    /**
     * Define a dynamic method to check the current case.
     * Example: the name of a case is "Active",
     * so isActive() return if the case is either "Active" or not.
     *
     * @throws ErrorException
     */
    public function __call(string $name, array $arguments): bool
    {
        $caseName = $this->getStringAfterIs(string: $name);

        return self::fromName(name: $caseName)->value === $this->value;
    }

    protected function getStringAfterIs(string $string): string|null
    {
        $pos = stripos($string, 'is');

        return $pos !== false ? substr($string, $pos + 2) : null;
    }

    public static function getType(): string
    {
        $rEnum = new ReflectionEnum(self::class);

        $rBackingType = $rEnum->getBackingType();
        return (string)$rBackingType;
    }
}
