/* USER */
CREATE TABLE public.user
(
    id SERIAL NOT NULL,
    username TEXT NOT NULL,
    email TEXT NOT NULL,
    pseudo TEXT,
    password TEXT NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT users_pkey PRIMARY KEY (id),
    CONSTRAINT users_email_unique UNIQUE (email),
    CONSTRAINT users_username_unique UNIQUE (username)

);

-- /* VIDEO */
CREATE TABLE public.video
(
    id               SERIAL NOT NULL,
    name             TEXT  NOT NULL,
    duration             INTEGER NOT NULL,
    source            TEXT NOT NULL,
    created_at       TIMESTAMP(0) WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    view       INTEGER,
    enabled     INTEGER,
    user_id          INTEGER NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_video_user FOREIGN KEY (user_id)
    REFERENCES public.user (id) ON DELETE CASCADE
);

/* VIDEO FORMAT */
CREATE TABLE public.video_format
(
    code INTEGER NOT NULL,
    uri TEXT,
    video_id INTEGER NOT NULL,
    CONSTRAINT fk_video_format_video FOREIGN KEY (video_id)
    REFERENCES public.video (id) ON DELETE CASCADE
);

/* COMMENT */
CREATE TABLE public.comment
(
    body TEXT NOT NULL,
    user_id INTEGER NOT NULL,
    video_id INTEGER NOT NULL,
    CONSTRAINT fk_comment_user FOREIGN KEY (user_id)
    REFERENCES public.user (id) ON DELETE CASCADE,
    CONSTRAINT fk_comment_video FOREIGN KEY (video_id)
    REFERENCES public.video (id) ON DELETE CASCADE
);
