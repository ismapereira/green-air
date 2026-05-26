## Descrição
Descreva as alterações introduzidas por este Pull Request. Qual foi a motivação? Que problema resolve?

## Como foi testado?
Descreva as etapas que executou para testar as suas alterações.

## Checklist de Contribuição e Segurança:
Antes de submeter, por favor verifique se o seu código cumpre os padrões do Green Air:

- [ ] O meu código segue o padrão MVC nativo do projeto (sem uso de frameworks externos).
- [ ] Entradas do utilizador foram devidamente validadas/limpas.
- [ ] Utilizei **PDO + Prepared Statements** nas queries para a Base de Dados.
- [ ] Incluí o **token CSRF** (`<input type="hidden" name="_csrf">`) nos novos formulários POST e validei-os no Controller.
- [ ] Utilize a classe `UploadHelper` para qualquer upload de novas imagens ou ficheiros.
- [ ] No JavaScript, utilizei `textContent` em vez de `innerHTML` na manipulação do DOM para evitar XSS.
- [ ] Atualizei a documentação correspondente na diretoria `docs/` (se aplicável).

## Imagens / Capturas de Ecrã (Se aplicável)
(Arraste aqui as imagens do antes e depois, caso tenha alterado a interface).
