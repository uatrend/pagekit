<?php

namespace Pagekit\View\Helper;

use Pagekit\View\View;

class MetaHelper implements HelperInterface, \IteratorAggregate
{
    protected array $metas = [];

    /**
     * {@inheritdoc}
     */
    public function register(View $view): void
    {
        $view->on('head', function ($event) use ($view) {
            $view->trigger('meta', [$this]);
            $event->addResult($this->render());
        }, 20);
    }

    /**
     * Adds meta tags.
     *
     * @param  $metas
     * @return self
     */
    public function __invoke(array $metas)
    {
        foreach ($metas as $name => $value) {
            $this->add($name, $value);
        }

        return $this;
    }

    /**
     * Gets a meta tag.
     *
     * @param  string $name
     */
    public function get($name): ?string
    {
        return isset($this->metas[$name]) ? $this->metas[$name] : null;
    }

    /**
     * Adds a meta tag.
     *
     * @param  string $name
     * @param  string $value
     */
    public function add($name, $value = ''): self
    {
        if ($value) {
            $this->metas[$name] = $value;
        }

        return $this;
    }
    
    /**
  * Removes a meta tag.
  *
  * @param  string $name
  */
 public function remove( $name ): self
	{
		if (isset($this->metas[$name])) {
			unset($this->metas[$name]);			
		} 
        
        return $this;
	}

    /**
     * Renders the meta tags.
     */
    public function render(): string
    {
        $output = '';

        foreach ($this->metas as $name => $value) {

            if (preg_match('/^link:?/i', $name)) {

                if (!isset($value['rel'])) {
                    $value['rel'] = substr($name, 5);
                }

                $attributes = '';
                foreach ($value as $attr => $val) {
                    $attributes .= sprintf(' %s="%s"', $attr, htmlspecialchars($val));
                }
                $output .= sprintf("        <link%s>\n", $attributes);

            } else {

                $value = htmlspecialchars($value);

                if ($name == 'title') {
                    $output .= sprintf("        <title>%s</title>\n", $value);
                } elseif ($name == 'base') {
                    $output .= sprintf("        <base href=\"%s\">\n", $value);
                } elseif ($name == 'canonical') {
                    $output .= sprintf("        <link rel=\"%s\" href=\"%s\">\n", $name, $value);
                } elseif (preg_match('/^(og|fb|twitter|article):/i', $name)) {
                    $output .= sprintf("        <meta property=\"%s\" content=\"%s\">\n", $name, $value);
                } else {
                    $output .= sprintf("        <meta name=\"%s\" content=\"%s\">\n", $name, $value);
                }
            }
        }

        return $output;
    }

    /**
     * Returns an iterator for meta tags.
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->metas);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'meta';
    }
}
