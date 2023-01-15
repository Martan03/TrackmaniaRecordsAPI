<?php

class RouterPresenter extends Presenter
{
    protected Presenter $presenter;

    public function process(array $params): void
    {
        $parsedURL = $this->parseURL($params[0]);

        if ($parsedURL[count($parsedURL) - 1] == "")
            unset($parsedURL[count($parsedURL) - 1]);
        if (empty($parsedURL[0]))
            $this->sendOutput('', array('HTTP/1.1 404 Not Found'));

        $presenterClass = $this->dashesIntoCamelNotation(
            array_shift($parsedURL) . "Presenter"
        );

        if (file_exists("Presenter/" . $presenterClass . ".php"))
            $this->presenter = new $presenterClass;
        else
            $this->sendOutput('', array('HTTP/1.1 404 Not Found'));

        $this->presenter->process($parsedURL);
   }

   /**
     * Parses url
     * @param string $url to be parsed
     * @return array of url subpaths
     */
    private function parseURL(string $url) : array
    {
        $parsedURL = parse_url($url);
        return explode("/", trim(ltrim($parsedURL["path"], "/")));
    }

    /**
     * Transforms text from dashes to camel notation
     * @param string $txt string to be transformed¨
     * @return string transformed text
     */
    private function dashesIntoCamelNotation(string $txt) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $txt)));
    }
}