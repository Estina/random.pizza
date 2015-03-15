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
            new \Twig_SimpleFunction('flavor', [$this, 'getFlavor'])
        ];
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function getFlavor($options)
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
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'pizza';
    }
}
