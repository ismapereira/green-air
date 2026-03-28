# Documentação — Green Air v2.0

Este diretório reúne a documentação técnica e operacional do projeto **Green Air**.

## Guias

- **[`INSTALLATION.md`](INSTALLATION.md)**: instalação local (XAMPP/Apache) e pré‑requisitos.
- **[`CONFIGURATION.md`](CONFIGURATION.md)**: variáveis do `.env`, chaves e ajustes de ambiente.
- **[`ARCHITECTURE.md`](ARCHITECTURE.md)**: visão da arquitetura (MVC), controllers, models, helpers e frontend.
- **[`API.md`](API.md)**: endpoints internos — mapa, clima (expandido), notificações.
- **[`DATABASE.md`](DATABASE.md)**: esquema do banco (v1.0 + migração v2.0), tabelas e relacionamentos.
- **[`SECURITY.md`](SECURITY.md)**: CSRF, rate limiting, RBAC, sessão segura e recomendações.
- **[`DEPLOYMENT.md`](DEPLOYMENT.md)**: checklist e passos para produção.
- **[`TROUBLESHOOTING.md`](TROUBLESHOOTING.md)**: resolução de problemas comuns (404, rewrite, geolocalização, e-mail).
- **[`CHANGELOG.md`](CHANGELOG.md)**: histórico completo de mudanças por versão.

## Como navegar

1. Comece pelo `INSTALLATION.md`, depois configure o `.env` em `CONFIGURATION.md`.
2. Para entender o funcionamento interno (rotas/controllers/models/helpers), leia `ARCHITECTURE.md`.
3. Para detalhes sobre segurança (CSRF, rate limiting, roles), veja `SECURITY.md`.
4. Para alterações recentes, consulte o `CHANGELOG.md`.
