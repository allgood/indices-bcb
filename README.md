Indices BCB
===========

Este projeto auxilia na obtenção de índices financeiros junto
ao Banco Central do Brasil e a aplicação dele sobre valores. 

Como usar:
----------

### Instalação

Para instalar a biblioteca através do composer:

```
composer require allgood/indices-bcb
```

### Uso das funções 

Para usar a biblioteca instalada pelo composer:

```
require_once "vendor/autoload.php"

use \Allgood\IndicesBCB\IndiceBCB;

$i = new IndiceBCB();

// obtém o último valor da série:
$ultimoIndice = $i->getUltimoValor();

// obtém os últimos 12 valores da série:
$ultimosDoze = $i->getUltimosDozeValores();

// obtém o percentual acumulado referente aos últimos 12 valores:
$percentual = IndiceBCB::valorAcumuladoDoPeriodo($a->getUltimosDozeValores());

```

Verificar o [arquivo fonte](src/IndicesBCB/IndiceBCB.php) para mais detalhes sobre as funções.


Licença
-------

Todo o código deste projeto está licensiado sob a GNU Lesser General Public License versão 3.

Pode ser utilizado inalterado em qualquer projeto fechado ou open source, alterações efetuadas precisam ser fornecidas em código aberto aos usuários do sistema.


Facilitou sua vida?
-------------------

Se o código do projeto ajudou você em uma tarefa complexa, considere fazer uma doação ao autor pelo PIX abaixo.

![image](https://user-images.githubusercontent.com/6070736/116247400-317e3680-a741-11eb-9434-9f226eec39b5.png)

Chave Pix: 80fd8916-1131-4844-917e-2732eaa2ba74
