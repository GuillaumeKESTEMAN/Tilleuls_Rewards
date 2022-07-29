CREATE TABLE "media_object" (
  "id" uuid PRIMARY KEY,
  "name" string NOT NULL,
  "file_path" string NOT NULL
);

CREATE TABLE "lot" (
  "id" uuid PRIMARY KEY,
  "name" string NOT NULL,
  "quantity" int NOT NULL DEFAULT 0,
  "message" string NOT NULL,
  "image" int
);

CREATE TABLE "reward" (
  "id" uuid PRIMARY KEY,
  "lot" int,
  "distributed" boolean NOT NULL DEFAULT false
);

CREATE TABLE "game" (
  "id" uuid PRIMARY KEY,
  "tweet" int NOT NULL,
  "player" int NOT NULL,
  "score" int,
  "creation_date" datetime NOT NULL DEFAULT (now()),
  "play_date" datetime,
  "reward" int NOT NULL
);

CREATE TABLE "tweet" (
  "id" uuid PRIMARY KEY,
  "player" int NOT NULL,
  "tweet_id" string NOT NULL
);

CREATE TABLE "player" (
  "id" uuid PRIMARY KEY,
  "name" string NOT NULL,
  "username" string NOT NULL,
  "twitterAccountId" int NOT NULL,
  "win_date" datetime
);

CREATE TABLE "twitter_account_to_follow" (
  "id" uuid PRIMARY KEY,
  "twitter_account_name" string NOT NULL,
  "twitter_account_username" string NOT NULL,
  "twitter_account_id" string NOT NULL,
  "active" bool NOT NULL
);

CREATE TABLE "twitter_hashtag" (
  "id" uuid PRIMARY KEY,
  "hashtag" string NOT NULL,
  "active" bool NOT NULL
);

CREATE TABLE "tweet_reply" (
  "id" uuid PRIMARY KEY,
  "name" string NOT NULL,
  "message" string NOT NULL
);

ALTER TABLE "lot" ADD FOREIGN KEY ("image") REFERENCES "media_object" ("id");

ALTER TABLE "reward" ADD FOREIGN KEY ("lot") REFERENCES "lot" ("id");

ALTER TABLE "game" ADD FOREIGN KEY ("tweet") REFERENCES "tweet" ("id");

ALTER TABLE "game" ADD FOREIGN KEY ("player") REFERENCES "player" ("id");

ALTER TABLE "game" ADD FOREIGN KEY ("reward") REFERENCES "reward" ("id");

ALTER TABLE "tweet" ADD FOREIGN KEY ("player") REFERENCES "player" ("id");
