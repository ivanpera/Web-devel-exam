--
-- Dump dei dati per la tabella `tipologia_posto`
--

INSERT INTO `tipologia_posto` (`codTipologia`, `nomeTipologia`) VALUES
(1, 'Per terra');

--
-- Dump dei dati per la tabella `luogo`
--

INSERT INTO `luogo` (`codLuogo`, `nome`, `indirizzo`, `urlMaps`, `capienzaMassima`) VALUES
(1, 'Arena di Verona', 'Via dell\'arena, 1', '', 11),
(2, 'Colosseo', 'Via del colosseo, 1', '', 11);

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`email`, `userPassword`, `nome`, `cognome`, `dataNascita`, `genere`, `dataIscrizione`, `organizzatore`, `amministratore`) VALUES
('alexsperanza@live.it', '434ea961e2cb040cdbc2288467da95a2358a4c9adb32c70f3e4296d6f1859c6054baff94f293dc35c974a77f53caca32d4a15662bf0cec858e6b719fd208a87d', 'Alex', 'Speranza', '1998-07-06', 'M', '2020-01-29', b'0', b'1');

--
-- Dump dei dati per la tabella `prenotazione`
--

INSERT INTO `prenotazione` (`codPrenotazione`, `dataEOra`, `costoTotale`, `differenzaGiorni`, `emailUtente`) VALUES
(1, '2020-01-30 15:24:00', 35, 1, 'alexsperanza@live.it');

--
-- Dump dei dati per la tabella `categoria_evento`
--

INSERT INTO `categoria_evento` (`codCategoria`, `nomeCategoria`) VALUES
(1, 'Concerto'),
(2, 'Mostra fotografica'),
(3, 'Open space');



--
-- Dump dei dati per la tabella `evento`
--

INSERT INTO `evento` (`codEvento`, `nomeEvento`, `dataEOra`, `NSFC`, `descrizione`, `nomeImmagine`, `codLuogo`, `emailOrganizzatore`) VALUES
(1, 'Concerto di Ivan', '2020-03-11 00:00:00', b'0', 'Ivan canta', '', 2, 'alexsperanza@live.it'),
(2, 'Concerto Di Alex', '2020-02-17 00:00:00', b'0', 'Alex canta', '', 1, 'alexsperanza@live.it');



--
-- Dump dei dati per la tabella `evento_ha_categoria`
--

INSERT INTO `evento_ha_categoria` (`codCategoria`, `codEvento`) VALUES
(1, 1),
(3, 1),
(1, 2);



--
-- Dump dei dati per la tabella `posto`
--

INSERT INTO `posto` (`codEvento`, `codPosto`, `costo`, `codTipologia`, `codPrenotazione`) VALUES
(1, 1, 5, 1, 1),
(1, 10, 5, 1, 1),
(1, 2, 5, 1, 1),
(1, 3, 5, 1, 1),
(1, 4, 5, 1, 1),
(1, 5, 5, 1, 1),
(1, 6, 5, 1, 1),
(1, 7, 5, 1, 1),
(1, 8, 5, 1, 1),
(1, 9, 5, 1, NULL),
(2, 1, 3, 1, NULL),
(2, 10, 3, 1, NULL),
(2, 2, 3, 1, NULL),
(2, 3, 3, 1, NULL),
(2, 4, 3, 1, NULL),
(2, 5, 3, 1, NULL),
(2, 6, 3, 1, NULL),
(2, 7, 3, 1, NULL),
(2, 8, 3, 1, NULL),
(2, 9, 3, 1, NULL);


