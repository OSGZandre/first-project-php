## Documentação Twig

É importante saber que as chaves não fazem parte da variável, mas sim da instrução print.  
Ao acessar variáveis ​​dentro de tags, não as coloque entre chaves.

**Exemplo:**

```twig
{% if name != false %}
    {% for row in name %}
        <h2>Lucky number: {{ number }}</h2>
        <!-- ENTRE DUAS CHAVES FAZ A CHAMADA -->
        <h2>Nome: {{ row.name }}</h2>
        <!-- ENTRE DUAS CHAVES FAZ A CHAMADA -->
        <hr />
    {% endfor %}
{% endif %}
```

### Se uma variável ou atributo não existir:

- Quando false, ele retorna null;
- Quando true, ele lança uma exceção.

### Você pode atribuir valores a variáveis ​​dentro de blocos de código.

As atribuições usam a tag `set`:

```twig
{% set name = 'Fabien' %}
{% set numbers = [1, 2] %}
{% set map = {'city': 'Paris'} %}
```

### Usar argumentos nomeados torna seus modelos mais explícitos sobre o significado dos valores que você passa como argumentos:

```twig
{{ data|convert_encoding('UTF-8', 'iso-2022-jp') }}
{# versus #}
{{ data|convert_encoding(from: 'iso-2022-jp', to: 'UTF-8') }}
```

### Argumentos nomeados também permitem que você ignore alguns argumentos para os quais você não deseja alterar o valor padrão:

```twig
{# o primeiro argumento é o formato da data, que usa o formato global se null for passado #}
{{ "now"|date(null, "Europe/Paris") }}

{# ou pule o valor do formato usando um argumento nomeado para o fuso horário #}
{{
```

### foreach

- O construtor foreach fornece uma maneira fácil de iterar sobre arrays e objetos Traversable. O foreach emitirá um erro quando usado com uma variável contendo um tipo de dado diferente ou com uma variável não inicializada.

-- foreach pode, opcionalmente, obter a chave (key) de cada elemento:

```php
foreach (expressão_iterável as $valor) {
    lista_de_instruções
}

foreach (expressão_iterável as $chave => $valor) {
    lista_de_instruções
}
```

A primeira forma percorre o iterável dado por iterable_expression. A cada iteração, o valor do elemento atual é atribuído à variável $valor.

A segunda forma irá, adicionalmente, atribuir a chave do elemento corrente à variável $chave a cada iteração.

#### Para transmitir um modelo, chame o stream()método:

```twig
$template->stream(['the' => 'variables', 'go' => 'here']);
```

#### Para transmitir um bloco de modelo específico, chame o streamBlock()método:

```twig
$template->streamBlock('block_name', ['the' => 'variables', 'go' => 'here']);
```

```
-- Observação

Os métodos stream()e streamBlock()retornam um iterável.
```

**autoescape** _string_

Define a estratégia de escape automático padrão ( name, html, js, css, url, html_attr, ou um retorno de chamada PHP que recebe o modelo "filename" e retorna a estratégia de escape a ser usada — o retorno de chamada não pode ser um nome de função para evitar colisão com estratégias de escape integradas); defina-a como falsepara desabilitar o escape automático. A nameestratégia de escape determina a estratégia de escape a ser usada para um modelo com base na extensão do nome de arquivo do modelo (esta estratégia não incorre em nenhuma sobrecarga em tempo de execução, pois o escape automático é feito em tempo de compilação).

**Crie seu próprio carregador**

- Todos os carregadores implementam \Twig\Loader\LoaderInterface:

```php
interface \Twig\Loader\LoaderInterface
{
    /**
     * Returns the source context for a given template logical name.
     *
     * @param string $name The template logical name
     *
     * @return \Twig\Source
     *
     * @throws \Twig\Error\LoaderError When $name is not found
     */
    public function getSourceContext($name);

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     *
     * @throws \Twig\Error\LoaderError When $name is not found
     */
    public function getCacheKey($name);

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     *
     * @return bool    true if the template is fresh, false otherwise
     *
     * @throws \Twig\Error\LoaderError When $name is not found
     */
    public function isFresh($name, $time);

    /**
     * Check if we have the source code of a template, given its name.
     *
     * @param string $name The name of the template to check if we can load
     *
     * @return bool    If the template source code is handled by this loader or not
     */
    public function exists($name);
}
O isFresh()método deve retornar truese o modelo em cache atual ainda estiver atualizado, considerando o horário da última modificação ou falsenão.

O getSourceContext()método deve retornar uma instância de \Twig\Source.
```

**markdown_to_html**

- O markdown_to_htmlfiltro converte um bloco de Markdown em HTML:

```twig
{% apply markdown_to_html %}
Title
=====


Hello!
{% endapply %}
```

- Observe que você pode recuar o conteúdo Markdown, pois os espaços em branco iniciais serão removidos consistentemente antes da conversão:

```twig
{% apply markdown_to_html %}
    Title
    =====

    Hello!
{% endapply %}
```

- Você também pode usar o filtro em um arquivo incluído ou em uma variável:

```twig
{{ include('some_template.markdown.twig')|markdown_to_html }}

{{ changelog|markdown_to_html }}
```
