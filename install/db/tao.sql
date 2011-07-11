DROP TABLE IF EXISTS "extensions";
CREATE TABLE "extensions" (
  "id" varchar(25) NOT NULL default '',
  "name" varchar(150) default NULL,
  "version" varchar(4) default NULL,
  "loaded" int NOT NULL,
  "loadAtStartUp" int NOT NULL,
  "ghost" int NOT NULL default 0,
  PRIMARY KEY ("id")
);

INSERT INTO "extensions" VALUES 
('generis','generis','2.1',1,1,0),
('tao','tao','2.1',1,1,0),
('taoGroups','Tao Groups','2.1',1,1,0),
('taoItems','Tao Items','2.1',1,1,0),
('taoResults','Tao Results','2.1',1,1,0),
('taoSubjects','Tao Subjects','2.1',1,1,0),
('taoTests','Tao Test','2.1',1,1,0),
('taoDelivery','taoDelivery','2.1',1,1,0),
('wfEngine','Workflow Engine Extension','2.1',1,1,0);

DROP TABLE IF EXISTS "models";
CREATE TABLE "models" (
  "modelID" serial,
  "modelURI" varchar(255) default NULL,
  "baseURI" varchar(255) default NULL,
  PRIMARY KEY  ("modelID")
);
CREATE INDEX "idx_models_modelURI" ON "models" ("modelURI");

INSERT INTO "models" VALUES 
(3,'http://www.tao.lu/datatypes/WidgetDefinitions.rdf#','http://www.tao.lu/datatypes/WidgetDefinitions.rdf#'),
(4,'http://www.w3.org/1999/02/22-rdf-syntax-ns#','http://www.w3.org/1999/02/22-rdf-syntax-ns#'),
(5,'http://www.w3.org/2000/01/rdf-schema#','http://www.w3.org/2000/01/rdf-schema#'),
(7,'http://www.tao.lu/Ontologies/generis.rdf#','http://www.tao.lu/Ontologies/generis.rdf#'),
(6,'http://www.tao.lu/Ontologies/TAO.rdf#','http://www.tao.lu/Ontologies/TAO.rdf#'),
(13,'http://www.tao.lu/Ontologies/TAOResult.rdf','http://www.tao.lu/Ontologies/TAOResult.rdf#'),
(10,'http://www.tao.lu/Ontologies/TAOItem.rdf#','http://www.tao.lu/Ontologies/TAOItem.rdf#'),
(11,'http://www.tao.lu/Ontologies/TAOGroup.rdf#','http://www.tao.lu/Ontologies/TAOGroup.rdf#'),
(9,'http://www.tao.lu/Ontologies/TAOTest.rdf#','http://www.tao.lu/Ontologies/TAOTest.rdf#'),
(12,'http://www.tao.lu/Ontologies/TAOSubject.rdf#','http://www.tao.lu/Ontologies/TAOSubject.rdf#'),
(14,'http://www.tao.lu/Ontologies/TAODelivery.rdf#','http://www.tao.lu/Ontologies/TAODelivery.rdf#'),
(15,'http://www.tao.lu/middleware/wfEngine.rdf#','http://www.tao.lu/middleware/wfEngine.rdf#'),
(17,'http://www.tao.lu/middleware/Rules.rdf#','http://www.tao.lu/middleware/Rules.rdf#');

DROP TABLE IF EXISTS "statements";
CREATE TABLE "statements" (
  "modelID" int NOT NULL default 0,
  "subject" varchar(255) default NULL,
  "predicate" varchar(255) default NULL,
  "object" text,
  "l_language" varchar(255) default NULL,
  "id" serial,
  "author" varchar(255) default NULL,
  "stread" varchar(255) default NULL,
  "stedit" varchar(255) default NULL,
  "stdelete" varchar(255) default NULL,
  "epoch" timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  ("id")
);
CREATE INDEX "idx_statements_modelID" ON "statements" ("modelID");
CREATE INDEX "k_sp" ON "statements" ("subject", "predicate");
CREATE INDEX "k_po" ON "statements" ("predicate", "object"/*!(255)*/);

DROP TABLE IF EXISTS "class_to_table";
CREATE TABLE "class_to_table" (
	"id" serial,
	"uri" VARCHAR(255) NOT NULL,
	"table" VARCHAR(64) NOT NULL,
	"topClass" VARCHAR(255) NOT NULL,
	PRIMARY KEY ("id") 
);
CREATE INDEX "idx_class_to_table_uri" ON "class_to_table" ("uri");

DROP TABLE IF EXISTS "resource_to_table";
CREATE TABLE "resource_to_table" (
	"id" serial,
	"uri" VARCHAR(255) NOT NULL,
	"table" VARCHAR(64) NOT NULL,
	PRIMARY KEY ("id")
);
CREATE INDEX "idx_resource_to_table_uri" ON "resource_to_table" ("uri");

DROP TABLE IF EXISTS "resource_has_class";
CREATE TABLE "resource_has_class" (
	"resource_id" int NOT NULL,
	"class_id" int NOT NULL,
	PRIMARY KEY ("resource_id", "class_id") 
);
