# Contribuindo com o Green Air

Obrigado por considerar contribuir com o **Green Air**.

Este repositório usa **PHP puro (MVC simples)**, MySQL, HTML/CSS/JS e prioriza código legível, seguro e fácil de manter.

## Como começar

- Leia a documentação em `docs/INDEX.md`
- Para rodar localmente, siga `docs/INSTALLATION.md` e `docs/CONFIGURATION.md`

## Formas de contribuir

- **Reportar bugs** (com passos para reproduzir)
- **Sugerir melhorias** (UX, performance, segurança)
- **Enviar Pull Requests** (correções, features, docs)

## Antes de abrir uma Issue

Inclua o máximo de contexto possível:

- **Ambiente**: Windows/Linux, versão do PHP, MySQL/MariaDB, servidor (Apache/XAMPP)
- **URL/base path** usado (ex.: `http://localhost/.../public/`)
- **Passos para reproduzir**
- **Comportamento esperado** vs **resultado atual**
- Logs relevantes (Apache/PHP), se houver

## Pull Requests

### Checklist

- O PR tem um objetivo claro e escopo enxuto
- Código segue o padrão existente (MVC simples, sem frameworks)
- Entradas do usuário continuam **validadas** e usando **PDO + prepared statements**
- Upload de imagens continua respeitando `MAX_FILE_SIZE` e `ALLOWED_IMAGE_TYPES`
- Atualizou a documentação em `docs/` quando necessário

### Fluxo sugerido

1. Faça um fork (ou trabalhe em uma branch se tiver permissão)
2. Crie uma branch a partir da `main`:
   - `feature/nome-curto` ou `fix/nome-curto`
3. Faça commits pequenos e descritivos
4. Abra o PR explicando:
   - **Resumo**
   - **Motivação**
   - **Como testar**
   - Prints (se houver mudança visual)

## Padrões de código

### PHP

- Prefira **clareza** a “mágica”
- Evite duplicação (DRY), mas sem abstrações desnecessárias
- Use `filter_var(...)`, `trim()` e validações explícitas em inputs
- Evite concatenar parâmetros em SQL; use **placeholders**

### JavaScript

- ES6+ sem dependências extras
- Evite variáveis globais (use IIFE como o projeto já faz)

### CSS

- Mantenha consistência visual com `public/assets/css/style.css` e `admin.css`
- Prefira classes reutilizáveis e layout responsivo

## Segurança

Se você encontrar uma vulnerabilidade:

- **Não** abra issue pública com detalhes exploráveis
- Descreva o problema de forma responsável e envie um reporte privado ao mantenedor do projeto

## Licença

Ao contribuir, você concorda que seu código será distribuído sob os termos da licença do projeto (ver `LICENSE`).

