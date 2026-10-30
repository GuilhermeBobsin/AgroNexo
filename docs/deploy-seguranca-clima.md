# Setup seguro de produção

## Primeiro administrador

O sistema não disponibiliza rota pública de registro. Em uma instalação nova, após configurar banco e executar as migrações, crie o primeiro administrador por SSH/terminal:

```sh
php artisan migrate --force
php artisan agronexo:setup-admin
```

O comando solicita nome, e-mail e senha sem ecoar a senha, exige no mínimo 12 caracteres e falha se já houver um admin. O cadastro web de usuários permite somente agrônomo e operador. O seeder de demonstração é limitado a `local` e `testing`; não rode seed em produção.

## Desenvolvimento local

O botão de atualização envia jobs para a fila e não chama a API dentro da requisição web. Para iniciar a aplicação local com servidor, worker e agendador, use:

```sh
composer run dev
```

Se estiver usando `php artisan serve` isoladamente, abra outro terminal e execute `php artisan queue:listen --tries=1 --timeout=0`. Para verificar jobs pendentes/falhos: `php artisan queue:monitor default` e `php artisan queue:failed`. A URL `https://api.open-meteo.com/v1` é apenas a base da API; a consulta de previsão completa usa `/v1/forecast` e parâmetros de latitude/longitude.

## Clima / Open-Meteo

Para demonstração acadêmica sem finalidade comercial, use o endpoint aberto e preserve a atribuição visível na tela de Inteligência:

```dotenv
OPEN_METEO_BASE_URL=https://api.open-meteo.com/v1
OPEN_METEO_API_KEY=
```

O modelo usa coordenadas da propriedade, portanto confirme latitude e longitude. Forecasts são estimativas de grade e não substituem medição local.

Se o uso mudar para comercial, contrate uma licença compatível e configure o endpoint e a chave emitidos pelo plano:

```dotenv
OPEN_METEO_BASE_URL=https://customer-api.open-meteo.com/v1
OPEN_METEO_API_KEY=<chave fornecida pela Open-Meteo>
```

Depois de alterar variáveis:

```sh
php artisan config:cache
```

## Filas e agendador

Configure `QUEUE_CONNECTION=database` ou Redis, aplique as migrações da aplicação/filas e mantenha um worker supervisionado. Também configure o cron do servidor para executar `php artisan schedule:run` a cada minuto. O agendador envia atualização climática por propriedade uma vez por hora; requisições manuais também entram na fila. Monitore `storage/logs` e a fila `failed_jobs`.

Antes da liberação, execute `php artisan test`, valide as migrações em uma cópia do banco de homologação e confirme que scheduler e worker processam uma sincronização real. Não use `APP_DEBUG=true` em produção.
