<?php

namespace PlayingWithFoodAndTraits;

trait HasAttributes
{
    public function attributes()
    {
        return get_object_vars($this);
    }

    public function toArray()
    {
        $attributes = $this->attributes();

        foreach ($attributes as $attributeName => $item) {
            if (! is_object($item)) {
                continue;
            }

            $attributes[$attributeName] = $item->toArray();
        }

        return $attributes;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        return $this->toJson(JSON_PRETTY_PRINT);
    }
}

class Ingredient
{
    use HasAttributes;

    private $name;

    public function __construct($name)
    {
        $this->name = ucwords($name);
    }
}

class Recipe
{
    use HasAttributes;

    private $ingredients;

    public function __construct()
    {
        $this->ingredients = new \ArrayObject;
    }

    public function addIngredient(Ingredient $ingredient)
    {
        $this->ingredients->append($ingredient);
    }

    public function toArray()
    {
        $attributes = [];

        foreach ($this->ingredients as $ingredients) {
            $attributes[] = $ingredients->toArray();
        }

        return ['ingredients' => $attributes];
    }
}

class Sandwich
{
    use HasAttributes;

    private $name;
    private $size;
    private $recipe;


    public function __construct($name, $size, Recipe $recipe)
    {
        $this->name   = $name;
        $this->size   = $size;
        $this->recipe = $recipe;
    }
}

class Beverage
{
    use HasAttributes;

    private $brand;
    private $bottleSize;
    private $flavor;

    public function __construct($brand, $bottleSize, $flavor)
    {
        $this->brand      = $brand;
        $this->bottleSize = $bottleSize;
        $this->flavor     = $flavor;
    }
}

class Order
{
    use HasAttributes;

    private $sandwich;
    private $beverage;

    public function __construct(Sandwich $sandwich, Beverage $beverage)
    {
        $this->sandwich = $sandwich;
        $this->beverage = $beverage;
    }
}

$recipe = new Recipe();
$recipe->addIngredient(new Ingredient('bread'));
$recipe->addIngredient(new Ingredient('hamburger'));

$order = new Order(
    new Sandwich('Big Mac', 'large', $recipe),
    new Beverage('Coca-Cola', 'medium', 'cola')
);

echo $order;
