INSERT INTO category (Name)
VALUES('Comedy'),('Romance'),('Horror'),('Animations'),('Action'),('Thriller')
SELECT * FROM category;

INSERT INTO product (p_Name,p_Price,p_Rentprice,p_Description,p_ReleaseDate,categoryID,p_Stock,p_ageRating,p_Duration,p_Starring,P_Director)
VALUES ('A Family Affair',13.99,4.99,'Surprising romance kicks off comic consequences for a young woman, her mother, and her movie-star boss as they face the complications of love, sex, and identity.','2024-06-28',2,30,'PG-13','113 min','Nicole Kidman, Joey King, Zac Efron, Sherry Cola, Liza Koshy','Richard LaGravenese'),
('Pride & Prejudice',19.99,4.99,'Sparks fly when spirited Elizabeth Bennet meets single, rich, and proud Mr. Darcy. But Mr. Darcy reluctantly finds himself falling in love with a woman beneath his class. Can each overcome their own pride and prejudice?','2005-09-16',2,30,'PG','135 min','Keira Knightley, Matthew Macfadyen, Rosamund Pike, Simon Woods, Donald Sutherland','Joe Wright'),
('The Fault In Our Stars',15.99,4.99,'In spite of a few years, the tumor-shrinking health care miracle that has bought her, Hazel has never been such a thing but terminal, her final thing inscribed upon identification. However, when a patient called Augustus Waters appears at Cancer Kid Service Group, the story of Hazel is all about to be entirely rewritten.','2014-05-16',2,30,'PG-13','125 min','Shailene Woodley, Ansel Elgort, Nat Wolff, Laura Dern, Sam Trammell','Josh Boone'),
('The Idea Of You',13.99,4.99,'40-year-old single mom Solène begins an unexpected romance with 24-year-old Hayes Campbell, the lead singer of August Moon, the hottest boy band on the planet.','2024-03-16',2,30,'R','116 min','Jon Levine, Graham Norton, Raymond Alexander Cham Jr., Nicholas Galitzine, Jordan Hall','Michael Showalter'),
("To All The Boys I've Loved Before",14.99,4.99,"A teenage girl's secret love letters are exposed and wreak havoc on her love life.
",'2018-08-16',2,30,'PG-13','100 min','Isabelle Beech, Christian Michael Cooper, Noah Centineo, Madeleine Arthur, Janel Parrish','Susan Johnson');
