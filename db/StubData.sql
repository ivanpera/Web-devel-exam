--
-- Dump dei dati per la tabella `luogo`
--

INSERT INTO `luogo` (`codLuogo`, `nome`, `indirizzo`, `capienzaMassima`) VALUES ('1', 'Arena di Verona', 'Viale dell\'arena 1, Verona, Italia', '50'), ('2', 'Colosseo', 'Via del colosseo 45, Roma, Italia', '40'), ('3', 'Giardino del frate', 'Piazza del convento 109, Bologna, Italia', '25');

--
-- Dump dei dati per la tabella `categoria_evento`
--

INSERT INTO `categoria_evento` (`codCategoria`, `nomeCategoria`) VALUES ('1', 'Concerto'), ('2', 'Mostra fotografica'), ('3', 'Intrattenimento'), ('4', 'Educatizione'), ('5', 'In notturna'), ('6', 'Ospiti famosi');

--
-- Dump dei dati per la tabella `tipologia_posto`
--

INSERT INTO `tipologia_posto` (`codTipologia`, `nomeTipologia`) VALUES ('1', 'Unico'), ('2', 'Sedia'), ('3', 'Poltrona');

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`email`, `userPassword`, `nome`, `cognome`, `dataNascita`, `genere`, `dataIscrizione`, `amministratore`) VALUES
('alex.speranza@studio.unibo.it', '3ae3249ea51b4453339ae5a6359c741d370b69b92620ae1c48aab36f8a343b6a73aad88d6d79e66b26602c5370644bb3d18f16a4672838411ba8b3d31e8ef041', 'Alex', 'Speranza', '1998-03-26', 'M', '2020-02-17', b'1'),
('ivan.perazzini@studio.unibo.it', 'df7f51b4ed8b04365b20dabf99788d1cb153a563ce8168848dc8be33c2b1dfe46da3cb14e328e9bc00ce80db7ccdb4a18d3d31c545fee383a6fa0a97764583df', 'Ivan', 'Perazzini', '1998-03-18', 'M', '2020-02-17', b'1'),
('alberto.passarani@tiscali.it', '01fc8800546398b7423d91286ead1357473528f939dde67df1ab4781a8ccb7803bd9360b06993340b7dbc8ad38144bdf5555babcc5724b159e18821060ae4e2c', 'Alberto', 'Passarani', '1969-08-04', 'M', '2020-02-17', b'0'),
('anselmo.rotaferri@gmail.com', '20eb2662ceb04863bdcc8238062e40b85d6897104dfeb942cbd50164de94d360ff2da721f7d1daed751d297e61dee7f0fbe01d399de8c58ea83b72684242d28a', 'Anselmo', 'Rotaferri', '2000-01-31', 'M', '2020-02-17', b'0'),
('claudia.mezzaneti@live.it', 'de3b1df281e9f06a411d9df468b7677cb52de358a960cb2d3422173ba65fcbaff13a7fad164925466e6410c69748734b9d837df609af94831a69d44824c45ba1', 'Claudia', 'Mezzaneti', '1985-05-17', 'F', '2020-02-17', b'0'),
('gigina.ferrai@gmail.com', '40eb4075ca6fc642036af2633c898f805f5867395c3e45507cf7e5a79155da27c829cd5abc2b40e211b21e74fe902fe3850f3d4e5af9ed454f28466035f4304c', 'Gigina', 'Ferrai', '1975-05-18', 'F', '2020-02-17', b'0');

--
-- Dump dei dati per la tabella `evento`
--
INSERT INTO `evento` (`codEvento`, `nomeEvento`, `dataEOra`, `NSFC`, `descrizione`, `nomeImmagine`, `codLuogo`, `emailOrganizzatore`) VALUES
(1, 'Nonna in tournÃ¨', '2020-05-05 19:00:00', b'0', 'Tappa veronese della Nonna neomelodica piÃ¹ amata dagli italiani!', 'NonnaConcerto.jpg', 1, 'gigina.ferrai@gmail.com'),
(2, 'Nonna in tournÃ¨', '2020-01-15 19:00:00', b'0', 'Tappa romana per la super Nonna in tournÃ¨!', 'NonnaColosseo.jpg', 2, 'gigina.ferrai@gmail.com'),
(3, 'Concertone di fine anno 99 Posse', '2019-12-21 22:00:00', b'1', 'Concerto di fine anno nel giardino del frate per tutti i fan piÃ¹ sfegatati dei 99 Posse!', '99posseConcerto.jpg', 3, 'alberto.passarani@tiscali.it'),
(4, 'Lucca Comics a Verona', '2020-04-30 08:00:00', b'0', 'Appuntamento straordinario per tutti gli appassionati di fumetti e non nei dintorni di Verona.', 'LuccaComicsVerona.png', 1, 'alberto.passarani@tiscali.it'),
(5, 'Spettacolo circense dei gladiatori', '2020-08-15 14:00:00', b'1', 'Assisti ai combattimenti tra gladiatori e belve feroci!', 'Gladiatori.jpg', 2, 'alberto.passarani@tiscali.it');

--
-- Dump dei dati per la tabella `evento_ha_categoria`
--

INSERT INTO `evento_ha_categoria` (`codCategoria`, `codEvento`) VALUES
(1, 1),
(5, 1),
(6, 1),
(1, 2),
(3, 2),
(6, 2),
(1, 3),
(5, 3),
(3, 4),
(6, 4),
(3, 5);


--
-- Dumping data for table `moderazione`
--

INSERT INTO `moderazione` (`emailModeratore`, `codEvento`) VALUES
('gigina.ferrai@gmail.com', 4);

--
-- Dumping data for table `osserva`
--

INSERT INTO `osserva` (`codEvento`, `emailUtente`) VALUES
(1, 'anselmo.rotaferri@gmail.com');

--
-- Dump dei dati per la tabella `posto`
--


INSERT INTO `posto` (`codEvento`, `codPosto`, `costo`, `codTipologia`, `codPrenotazione`) VALUES
(1, 1, 3000, 1, NULL),
(1, 2, 3000, 1, NULL),
(1, 3, 3000, 1, NULL),
(1, 4, 3000, 1, NULL),
(1, 5, 3000, 1, NULL),
(1, 6, 3000, 1, NULL),
(1, 7, 3000, 1, NULL),
(1, 8, 3000, 1, NULL),
(1, 9, 3000, 1, NULL),
(1, 10, 3000, 1, NULL),
(1, 11, 3000, 1, NULL),
(1, 12, 3000, 1, NULL),
(1, 13, 3000, 1, NULL),
(1, 14, 3000, 1, NULL),
(1, 15, 3000, 1, NULL),
(1, 16, 3000, 1, NULL),
(1, 17, 3000, 1, NULL),
(1, 18, 3000, 1, NULL),
(1, 19, 3000, 1, NULL),
(1, 20, 3000, 1, NULL),
(1, 21, 3000, 1, NULL),
(1, 22, 3000, 1, NULL),
(1, 23, 3000, 1, NULL),
(1, 24, 3000, 1, NULL),
(1, 25, 3000, 1, NULL),
(1, 26, 3000, 1, NULL),
(1, 27, 3000, 1, NULL),
(1, 28, 3000, 1, NULL),
(1, 29, 3000, 1, NULL),
(1, 30, 3000, 1, NULL),
(1, 31, 3000, 1, NULL),
(1, 32, 3000, 1, NULL),
(1, 33, 3000, 1, NULL),
(1, 34, 3000, 1, NULL),
(1, 35, 3000, 1, NULL),
(1, 36, 3000, 1, NULL),
(1, 37, 3000, 1, NULL),
(1, 38, 3000, 1, NULL),
(1, 39, 3000, 1, NULL),
(1, 40, 3000, 1, NULL),
(1, 41, 3000, 1, NULL),
(1, 42, 3000, 1, NULL),
(1, 43, 3000, 1, NULL),
(1, 44, 3000, 1, NULL),
(1, 45, 3000, 1, NULL),
(1, 46, 3000, 1, NULL),
(1, 47, 3000, 1, NULL),
(1, 48, 3000, 1, NULL),
(1, 49, 3000, 1, NULL),
(1, 50, 3000, 1, NULL),
(2, 1, 2000, 1, NULL),
(2, 2, 2000, 1, NULL),
(2, 3, 2000, 1, NULL),
(2, 4, 2000, 1, NULL),
(2, 5, 2000, 1, NULL),
(2, 6, 2000, 1, NULL),
(2, 7, 2000, 1, NULL),
(2, 8, 2000, 1, NULL),
(2, 9, 2000, 1, NULL),
(2, 10, 2000, 1, NULL),
(2, 11, 2000, 1, NULL),
(2, 12, 2000, 1, NULL),
(2, 13, 2000, 1, NULL),
(2, 14, 2000, 1, NULL),
(2, 15, 2000, 1, NULL),
(2, 16, 2000, 1, NULL),
(2, 17, 2000, 1, NULL),
(2, 18, 2000, 1, NULL),
(2, 19, 2000, 1, NULL),
(2, 20, 2000, 1, NULL),
(2, 21, 2000, 1, NULL),
(2, 22, 2000, 1, NULL),
(2, 23, 2000, 1, NULL),
(2, 24, 2000, 1, NULL),
(2, 25, 2000, 1, NULL),
(2, 26, 2000, 1, NULL),
(2, 27, 2000, 1, NULL),
(2, 28, 2000, 1, NULL),
(2, 29, 2000, 1, NULL),
(2, 30, 2000, 1, NULL),
(2, 31, 3000, 2, NULL),
(2, 32, 3000, 2, NULL),
(2, 33, 3000, 2, NULL),
(2, 34, 3000, 2, NULL),
(2, 35, 3000, 2, NULL),
(2, 36, 3000, 2, NULL),
(2, 37, 3000, 2, NULL),
(2, 38, 3000, 2, NULL),
(2, 39, 3000, 2, NULL),
(2, 40, 3000, 2, NULL),
(3, 1, 5000, 1, 1),
(3, 2, 5000, 1, 1),
(3, 3, 5000, 1, NULL),
(3, 4, 5000, 1, NULL),
(3, 5, 5000, 1, NULL),
(3, 6, 5000, 1, NULL),
(3, 7, 5000, 1, NULL),
(3, 8, 5000, 1, NULL),
(3, 9, 5000, 1, NULL),
(3, 10, 5000, 1, NULL),
(3, 11, 5000, 1, NULL),
(3, 12, 5000, 1, NULL),
(3, 13, 5000, 1, NULL),
(3, 14, 5000, 1, NULL),
(3, 15, 5000, 1, NULL),
(3, 16, 5000, 1, NULL),
(3, 17, 5000, 1, NULL),
(3, 18, 5000, 1, NULL),
(3, 19, 5000, 1, NULL),
(3, 20, 5000, 1, NULL),
(3, 21, 5000, 1, NULL),
(3, 22, 5000, 1, NULL),
(3, 23, 5000, 1, NULL),
(3, 24, 5000, 1, NULL),
(3, 25, 5000, 1, NULL),
(4, 1, 2500, 1, 2),
(4, 2, 2500, 1, 2),
(4, 3, 2500, 1, 2),
(4, 4, 2500, 1, 2),
(4, 5, 2500, 1, 2),
(4, 6, 2500, 1, NULL),
(4, 7, 2500, 1, NULL),
(4, 8, 2500, 1, NULL),
(4, 9, 2500, 1, NULL),
(4, 10, 2500, 1, NULL),
(4, 11, 2500, 1, NULL),
(4, 12, 2500, 1, NULL),
(4, 13, 2500, 1, NULL),
(4, 14, 2500, 1, NULL),
(4, 15, 2500, 1, NULL),
(4, 16, 2500, 1, NULL),
(4, 17, 2500, 1, NULL),
(4, 18, 2500, 1, NULL),
(4, 19, 2500, 1, NULL),
(4, 20, 2500, 1, NULL),
(4, 21, 2500, 1, NULL),
(4, 22, 2500, 1, NULL),
(4, 23, 2500, 1, NULL),
(4, 24, 2500, 1, NULL),
(4, 25, 2500, 1, NULL),
(4, 26, 2500, 1, NULL),
(4, 27, 2500, 1, NULL),
(4, 28, 2500, 1, NULL),
(4, 29, 2500, 1, NULL),
(4, 30, 2500, 1, NULL),
(4, 31, 2500, 1, NULL),
(4, 32, 2500, 1, NULL),
(4, 33, 2500, 1, NULL),
(4, 34, 2500, 1, NULL),
(4, 35, 2500, 1, NULL),
(4, 36, 2500, 1, NULL),
(4, 37, 2500, 1, NULL),
(4, 38, 2500, 1, NULL),
(4, 39, 2500, 1, NULL),
(4, 40, 2500, 1, NULL),
(4, 41, 3000, 2, NULL),
(4, 42, 3000, 2, NULL),
(4, 43, 3000, 2, NULL),
(4, 44, 3000, 2, NULL),
(4, 45, 3000, 2, NULL),
(4, 46, 3000, 2, NULL),
(4, 47, 3000, 2, NULL),
(4, 48, 5000, 3, NULL),
(4, 49, 5000, 3, NULL),
(4, 50, 5000, 3, NULL),
(5, 1, 1000, 2, NULL),
(5, 2, 1000, 2, NULL),
(5, 3, 1000, 2, NULL),
(5, 4, 1000, 2, NULL),
(5, 5, 1000, 2, NULL),
(5, 6, 1000, 2, NULL),
(5, 7, 1000, 2, NULL),
(5, 8, 1000, 2, NULL),
(5, 9, 1000, 2, NULL),
(5, 10, 1000, 2, NULL),
(5, 11, 1000, 2, NULL),
(5, 12, 1000, 2, NULL),
(5, 13, 1000, 2, NULL),
(5, 14, 1000, 2, NULL),
(5, 15, 1000, 2, NULL),
(5, 16, 1000, 2, NULL),
(5, 17, 1000, 2, NULL),
(5, 18, 1000, 2, NULL),
(5, 19, 1000, 2, NULL),
(5, 20, 1000, 2, NULL),
(5, 21, 1000, 2, NULL),
(5, 22, 1000, 2, NULL),
(5, 23, 1000, 2, NULL),
(5, 24, 1000, 2, NULL),
(5, 25, 1000, 2, NULL),
(5, 26, 1000, 2, NULL),
(5, 27, 1000, 2, NULL),
(5, 28, 1000, 2, NULL),
(5, 29, 1000, 2, NULL),
(5, 30, 1000, 2, NULL),
(5, 31, 1000, 2, NULL),
(5, 32, 1000, 2, NULL),
(5, 33, 1000, 2, NULL),
(5, 34, 1000, 2, NULL),
(5, 35, 1000, 2, NULL),
(5, 36, 2500, 3, NULL),
(5, 37, 2500, 3, NULL),
(5, 38, 2500, 3, NULL),
(5, 39, 2500, 3, NULL),
(5, 40, 2500, 3, NULL);


--
-- Dumping data for table `prenotazione`
--

INSERT INTO `prenotazione` (`codPrenotazione`, `dataEOra`, `costoTotale`, `emailUtente`) VALUES
(1, '2019-11-19 12:00:00', 10000, 'claudia.mezzaneti@live.it'),
(2, '2020-02-17 19:07:05', 12500, 'anselmo.rotaferri@gmail.com');

--
-- Dumping data for table `recensione`
--

INSERT INTO `recensione` (`codEvento`, `emailUtente`, `voto`, `testo`, `anonima`, `dataScrittura`) VALUES
(3, 'claudia.mezzaneti@live.it', 4, 'Bellissimo concerto, un peccato non aver pulito il giardino dopo.', b'0', '2020-02-17');