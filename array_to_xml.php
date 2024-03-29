<?php
/************************************************************************/
/*                                                                      */
/* Soubor: array_to_xml.php                                             */
/* Vytvoren: 2022-02-16                                                 */
/* Posledni zmena: 2022-03-06                                           */
/* Autor: David Chocholaty <xchoch09@stud.fit.vutbr.cz>                 */
/* Projekt: Uloha 1 pro predmet IPP                                     */
/* Popis: Skript se tridou reprezentujici prevodnik do XML reprezentace */ 
/*                                                                      */
/************************************************************************/

/*
 * Trida reprezentujici prevodnik pole do xml
 * 
 * Zdrojovy kod tridy je inspirovany nasledujicim originalnim kodem:
 * 
 * Originalni kod: https://github.com/spatie/array-to-xml/blob/main/src/ArrayToXml.php
 * Licence: MIT License
 * Vlastnik repozitare: Spatie
 */
class Array2Xml {
    private DOMDocument $document;
    private bool $addXmlDeclaration;
    private string $numericTagNamePrefix = 'numeric_';

    /*
     * Konstruktor
     *
     * @param $array             Vstupni pole
     * @param $rootElement       Korenovy element
     * @param $xmlVersion        Verze XML
     * @param $xmlEncoding       Kodovani XML
     * @param $formatOutput      Formatovani vystupu
     * @param $addXmlDeclaration Pridani deklarace XML
     */
    private function __construct(array $array,
                                 string | array $rootElement,
                                 string $xmlVersion,
                                 string $xmlEncoding,
                                 bool $formatOutput,
                                 bool $addXmlDeclaration) {
        $this->document = new DOMDocument($xmlVersion, $xmlEncoding);
        $this->document->formatOutput = $formatOutput;

        if ($this->isArrayAllKeySequential($array) && !empty($array)) {
            throw new DOMException('Invalid Character Error');
        }

        $root = $this->createRootElement($rootElement);
        $this->document->appendChild($root);
        $this->convertElement($root, $array);

        $this->addXmlDeclaration = $addXmlDeclaration;
    }

    /*
     * Metoda pro urceni, zda jsou vsechny klice pole sekvencni
     *
     * @param $value Hodnota
     * @return       V pripade splneni podminek sekvencnosti true, jinak false
     */
    private function isArrayAllKeySequential(array | string | null $value): bool {
        if (! is_array($value)) {
            return false;
        }

        if (count($value) <= 0) {
            return true;
        }

        if (\key($value) === '__numeric') {
            return false;
        }

        return array_unique(array_map('is_int', array_keys($value))) === [true];
    }

    /*
     * Metoda pro vytvoreni korenoveho elementu
     *
     * @param $rootElement Korenovy element
     * @return             XML reprezentace korenoveho elementu
     */
    private function createRootElement($rootElement) : DOMElement {
        if(is_string($rootElement)) {
            $rootElementName = $rootElement ?: 'root';

            return $this->document->createElement($rootElementName);
        }

        $rootElementName = $rootElement['rootElementName'] ?? 'root';

        $element = $this->document->createElement($rootElementName);

        foreach ($rootElement as $key => $value) {
            if ($key !== '_attributes' && $key !== '@attributes') {
                continue;
            }

            $this->addAttributes($element, $rootElement[$key]);
        }

        return $element;
    }

    /*
     * Metoda pro odstraneni ridicich znaku
     *
     * @param $value Vstupni hodnota
     * @return       Vstupni hodnota bez ridicich znaku
     */
    private function removeControlCharacters(string $value): string
    {
        return preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
    }

    /*
     * Metoda pro pridani atributu
     *
     * @param $element Vstupni element
     * @param $data    Data elementu
     */
    private function addAttributes(DOMElement $element, array $data): void {
        foreach ($data as $attrKey => $attrVal) {
            $element->setAttribute($attrKey, $attrVal);
        }
    }

    /*
     * Metoda pro pridani ciselneho uzlu
     *
     * @param $element Vstupni element
     * @param $value   Hodnota
     */
    private function addNumericNode(DOMElement $element, $value): void {
        foreach ($value as $key => $item) {
            $this->convertElement($element, [$this->numericTagNamePrefix.$key => $item]);
        }
    }

    /*
     * Metoda pro pridani uzlu
     *
     * @param $element Vstupni element
     * @param $key     Klic
     * @param $value   Hodnota
     */
    private function addNode(DOMElement $element, $key, $value): void {        
        $child = $this->document->createElement($key);
        $element->appendChild($child);
        $this->convertElement($child, $value);
    }

    /*
     * Metoda pro pridani uzlu kolekce
     *
     * @param $element Vstupni element
     * @param $value   Hodnota
     */
    private function addCollectionNode(DOMElement $element, $value): void {
        if ($element->childNodes->length === 0 && $element->attributes->length === 0) {
            $this->convertElement($element, $value);

            return;
        }

        $child = $this->document->createElement($element->tagName);
        $element->parentNode->appendChild($child);
        $this->convertElement($child, $value);
    }

    /*
     * Metoda pro pridani sekvencniho uzlu
     *
     * @param $element Vstupni element
     * @param $value   Hodnota
     */
    private function addSequentialNode(DOMElement $element, $value): void {
        if (empty($element->nodeValue) && ! is_numeric($element->nodeValue)) {
            $element->nodeValue = htmlspecialchars($value);

            return;
        }

        $child = new DOMElement($element->tagName);
        $child->nodeValue = htmlspecialchars($value);
        $element->parentNode->appendChild($child);
    }

    /*
     * Metoda pro prevod elementu do jeho XML reprezentace
     *
     * @param $element Vstupni element
     * @param $value   Hodnota
     */
    private function convertElement(DOMElement $element, mixed $value): void {
        $sequential = $this->isArrayAllKeySequential($value);

        if (! is_array($value)) {
            $value = htmlspecialchars($value);
            $value = $this->removeControlCharacters($value);
            $element->nodeValue = $value;

            return;
        }

        foreach ($value as $key => $data) {            
            if (! $sequential) {
                if (($key === '_attributes') || ($key === '@attributes')) {
                    $this->addAttributes($element, $data);
                } elseif ((($key === '_value') || ($key === '@value')) && is_string($data)) {
                    $element->nodeValue = htmlspecialchars($data);
                } elseif ((($key === '_cdata') || ($key === '@cdata')) && is_string($data)) {
                    $element->appendChild($this->document->createCDATASection($data));
                } elseif ((($key === '_mixed') || ($key === '@mixed')) && is_string($data)) {
                    $fragment = $this->document->createDocumentFragment();
                    $fragment->appendXML($data);
                    $element->appendChild($fragment);
                } elseif ($key === '__numeric') {
                    $this->addNumericNode($element, $data);
                } elseif (str_starts_with($key, '__custom:')) {
                    $this->addNode($element, str_replace('\:', ':', preg_split('/(?<!\\\):/', $key)[1]), $data);
                } else {                    
                    $this->addNode($element, $key, $data);
                }
            } elseif (is_array($data)) {
                $this->addCollectionNode($element, $data);
            } else {
                $this->addSequentialNode($element, $data);
            }
        }
    }

    /*
     * Metoda pro ulozeni prevedene XML reprezentace
     *
     * @return Vysledna XML reprezentace
     */
    private function toXml() : string {
        return $this->addXmlDeclaration
            ? $this->document->saveXML()
            : $this->document->saveXml($this->document->documentElement);
    }

    /*
     * Verejna metoda pro prevod pole do xml
     *
     * @param $array             Vstupni pole
     * @param $rootElement       Korenovy element
     * @param $xmlVersion        Verze XML
     * @param $xmlEncoding       Kodovani XML
     * @param $formatOutput      Formatovani vystupu
     * @param $addXmlDeclaration Pridani deklarace XML
     * @return                   XML reprezentace pole
     */
    public static function convert(array $array,
                                   $rootElement = '',
                                   string $xmlVersion = '1.0',
                                   string $xmlEncoding = 'UTF-8',
                                   bool $formatOutput = true,
                                   bool $addXmlDeclaration = true) : string {
        $converter = new static($array,
                                $rootElement,
                                $xmlVersion,
                                $xmlEncoding,                                
                                $formatOutput,
                                $addXmlDeclaration);
        return $converter->toXml();
    }
}

?>
