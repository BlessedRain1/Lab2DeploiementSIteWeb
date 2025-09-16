DROP TABLE IF EXISTS Exercice_Categories;

CREATE TABLE Exercice_Categories
(
  ID integer PRIMARY KEY,
  Nom nvarchar(20)
);

INSERT INTO Exercice_Categories(ID, Nom)
VALUES 
 (1, "Berline"),
 (2, "Compacte"),
 (3, "Sous-Compacte"),
 (4, "VUS compact"),
 (5, "Speciale"),
 (6, "VUS intermediaire"),
 (7, "Camionnette"),
 (8, "Fourgonnette");
