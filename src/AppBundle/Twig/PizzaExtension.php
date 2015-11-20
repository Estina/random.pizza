<?php

namespace AppBundle\Twig;


class PizzaExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('resultTitle', [$this, 'getTitle']),
            new \Twig_SimpleFunction('resultMetaDescription', [$this, 'getMetaDescription']),
            new \Twig_SimpleFunction('resultMetaKeywords', [$this, 'getMetaKeywords']),
            new \Twig_SimpleFunction('resultDescription', [$this, 'getDescription'], ['is_safe' => array('html')])
        ];
    }

    /**
     * @param array $generatedResult
     *
     * @return string
     */
    public function getTitle($generatedResult)
    {
        return sprintf(
            '%d Random Pizza%s in %s, %s',
            $generatedResult['options']->qty,
            ($generatedResult['options']->qty > 1) ? 's' : '',
            $generatedResult['restaurant_name'],
            $generatedResult['city']
        );
    }

    /**
     * @param array $generatedResult
     *
     * @return string
     */
    public function getMetaDescription($generatedResult)
    {
        return sprintf(
            '%d random (%s) pizza%s for %s located in %s: %s',
            $generatedResult['options']->qty,
            $this->getFlavor($generatedResult['options']),
            ($generatedResult['options']->qty > 1) ? 's' : '',
            $generatedResult['restaurant_name'],
            $generatedResult['city'],
            $this->getPizzas($generatedResult['pizzas'])
        );
    }

    /**
     * @param array $generatedResult
     *
     * @return string
     */
    public function getMetaKeywords($generatedResult)
    {
        return sprintf(
            'random.pizza, random pizza, random pizza generator, %s, %s, %s',
            $generatedResult['restaurant_name'],
            $generatedResult['city'],
            $this->getPizzas($generatedResult['pizzas'])
        );
    }

    /**
     * @param array $generatedResult
     *
     * @return string
     */
    public function getDescription($generatedResult)
    {
        $result = sprintf(
            'The request was to generate <strong>%d</strong> random <strong>(%s)</strong> pizza%s ',
            $generatedResult['options']->qty,
            $this->getFlavor($generatedResult['options']),
            ($generatedResult['options']->qty > 1) ? 's' : ''
        );

        if (!empty($generatedResult['url'])) {
            $restaurant = sprintf(
                '<a href="http://%s" target="_blank">"%s"</a>',
                $generatedResult['url'],
                $generatedResult['restaurant_name']
            );
        } else {
            $restaurant = sprintf('"<strong>%s</strong>"', $generatedResult['restaurant_name']);
        }

        if (0 == $generatedResult['options']->restaurantId) {
            $result .= sprintf(
                'for <strong>random</strong> restaurant located in <strong>%s</strong>. So, I\'ve picked "%s" for you!',
                $generatedResult['city'],
                $restaurant
            );
        } else {
            $result .= sprintf(
                'for %s located in <strong>%s</strong>.',
                $restaurant,
                $generatedResult['city']
            );
        }

        return $result;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    private function getFlavor($options)
    {
        $result = '';
        $available = [];
        $flavors = ['meat', 'fish', 'vegetarian', 'hot'];
        foreach ($options as $opt => $value) {
            if (in_array($opt, $flavors) && true === $value) {
                $available[] = $opt;
            }
        }

        if ($available) {
            if (1 < count($available)) {
                $lastFlavor = array_pop($available);
                $result = implode(', ', $available) . ' or ' . $lastFlavor;
            } else {
                $result = $available[0];
            }

            $result = str_replace('meat', 'meaty', $result);
            $result = str_replace('fish', 'fishy', $result);
            $result = str_replace('vegetarian', 'veggy', $result);
        }

        return $result;
    }

    /**
     * @param array $pizzas
     *
     * @return string
     */
    private function getPizzas($pizzas)
    {
        $result  = [];
        foreach ($pizzas as $pizza) {
            if (1 < $pizza['qty']) {
                $result[] = $pizza['name'] . ' x ' . $pizza['qty'];
            } else {
                $result[] = $pizza['name'];
            }
        }

        return implode(', ', $result);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'pizza';
    }
}
