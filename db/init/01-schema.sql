--
-- PostgreSQL database dump
--

\restrict YKQmch2d1TBx8do79bLQPwafEzlqXwjdriU98MMicVCSdM7cRIfJD306zDCENbT

-- Dumped from database version 15.18 (Ubuntu 15.18-201-yandex.55830.ec642aad1f)
-- Dumped by pg_dump version 15.18 (Debian 15.18-1.pgdg13+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

-- *not* creating schema, since initdb creates it


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: ki_add_uri; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_add_uri (
    add_uri_id bigint NOT NULL,
    uri text DEFAULT NULL::character varying,
    allrpg_info_id bigint,
    resolved smallint DEFAULT '0'::smallint
);


--
-- Name: ki_add_uri_add_uri_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_add_uri_add_uri_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_add_uri_add_uri_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_add_uri_add_uri_id_seq OWNED BY public.ki_add_uri.add_uri_id;


--
-- Name: ki_game_date; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_game_date (
    game_date_id bigint NOT NULL,
    game_id bigint,
    begin date,
    "time" smallint,
    hidden_flag smallint,
    "order" bigint
);


--
-- Name: ki_game_date_game_date_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_game_date_game_date_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_game_date_game_date_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_game_date_game_date_id_seq OWNED BY public.ki_game_date.game_date_id;


--
-- Name: ki_game_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_game_types (
    game_type_id bigint NOT NULL,
    game_type_name text DEFAULT ''::character varying,
    show_all_regions smallint DEFAULT '0'::smallint,
    game_type_style text DEFAULT ''::character varying,
    game_type_real_game smallint DEFAULT '0'::smallint
);


--
-- Name: ki_game_types_game_type_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_game_types_game_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_game_types_game_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_game_types_game_type_id_seq OWNED BY public.ki_game_types.game_type_id;


--
-- Name: ki_games; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_games (
    id bigint NOT NULL,
    name text DEFAULT ''::character varying,
    uri text DEFAULT ''::character varying,
    type smallint DEFAULT '0'::smallint,
    polygon integer DEFAULT 0,
    mg text DEFAULT ''::character varying,
    email text DEFAULT ''::character varying,
    show_flags bigint DEFAULT '0'::bigint,
    status smallint DEFAULT '0'::smallint,
    comment text DEFAULT ''::character varying,
    sub_region_id bigint DEFAULT '0'::bigint,
    deleted_flag smallint DEFAULT '0'::smallint,
    hide_email smallint DEFAULT '0'::smallint,
    players_count bigint,
    review_count bigint DEFAULT '0'::bigint,
    allrpg_info_id bigint,
    photo_count bigint DEFAULT '0'::bigint,
    redirect_id bigint,
    vk_likes bigint DEFAULT '0'::bigint,
    vk_club text DEFAULT NULL::character varying,
    lj_comm text DEFAULT NULL::character varying,
    fb_comm text DEFAULT NULL::character varying,
    telegram_channel text DEFAULT NULL::character varying,
    telegram_contact text DEFAULT NULL::character varying
);


--
-- Name: ki_games_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_games_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_games_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_games_id_seq OWNED BY public.ki_games.id;


--
-- Name: ki_photo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_photo (
    photo_id bigint NOT NULL,
    game_id bigint,
    photo_author text DEFAULT NULL::character varying,
    photo_uri text,
    author_id bigint,
    photo_comment text DEFAULT NULL::character varying,
    photo_good_flag smallint DEFAULT '0'::smallint
);


--
-- Name: ki_photo_photo_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_photo_photo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_photo_photo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_photo_photo_id_seq OWNED BY public.ki_photo.photo_id;


--
-- Name: ki_polygons; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_polygons (
    polygon_id bigint NOT NULL,
    polygon_name text DEFAULT ''::character varying,
    sub_region_id bigint DEFAULT '0'::bigint,
    meta_polygon bigint DEFAULT '0'::bigint,
    deleted_flag smallint DEFAULT '0'::smallint
);


--
-- Name: ki_polygons_polygon_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_polygons_polygon_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_polygons_polygon_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_polygons_polygon_id_seq OWNED BY public.ki_polygons.polygon_id;


--
-- Name: ki_regions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_regions (
    region_id integer NOT NULL,
    region_name text DEFAULT ''::character varying,
    region_code text DEFAULT ''::character varying,
    region_experimental smallint DEFAULT '0'::smallint
);


--
-- Name: ki_regions_region_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_regions_region_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_regions_region_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_regions_region_id_seq OWNED BY public.ki_regions.region_id;


--
-- Name: ki_review; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_review (
    review_id bigint NOT NULL,
    game_id bigint,
    author_name text DEFAULT NULL::character varying,
    topic_id bigint,
    review_uri text DEFAULT NULL::character varying,
    show_review_flag smallint,
    author_id bigint
);


--
-- Name: ki_review_review_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_review_review_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_review_review_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_review_review_id_seq OWNED BY public.ki_review.review_id;


--
-- Name: ki_status; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_status (
    status_id bigint NOT NULL,
    status_name text,
    status_style text,
    problem_status smallint,
    future_only_status smallint,
    cancelled_status smallint DEFAULT '0'::smallint,
    show_review_flag smallint DEFAULT '0'::smallint,
    show_date_flag smallint DEFAULT '0'::smallint,
    good_status smallint DEFAULT '0'::smallint
);


--
-- Name: ki_sub_regions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_sub_regions (
    sub_region_id bigint NOT NULL,
    sub_region_name text DEFAULT ''::character varying,
    sub_region_disp_name text DEFAULT ''::character varying,
    region_id bigint DEFAULT '0'::bigint
);


--
-- Name: ki_sub_regions_sub_region_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_sub_regions_sub_region_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_sub_regions_sub_region_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_sub_regions_sub_region_id_seq OWNED BY public.ki_sub_regions.sub_region_id;


--
-- Name: ki_update_types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_update_types (
    ki_update_type_id bigint NOT NULL,
    ki_update_type_name text DEFAULT ''::character varying,
    update_type_polygon_flag smallint DEFAULT '0'::smallint,
    update_type_game_flag smallint DEFAULT '0'::smallint,
    update_type_photo_flag smallint DEFAULT '0'::smallint,
    update_type_review_flag smallint DEFAULT '0'::smallint,
    advertise_update_flag smallint DEFAULT '0'::smallint,
    update_type_user_text text DEFAULT NULL::character varying
);


--
-- Name: ki_update_types_ki_update_type_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_update_types_ki_update_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_update_types_ki_update_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_update_types_ki_update_type_id_seq OWNED BY public.ki_update_types.ki_update_type_id;


--
-- Name: ki_updates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_updates (
    ki_update_id bigint NOT NULL,
    ki_update_type_id bigint DEFAULT '0'::bigint,
    user_id bigint DEFAULT '0'::bigint,
    update_date timestamp with time zone,
    game_id bigint,
    polygon_id bigint,
    photo_id bigint,
    updated_user_id bigint,
    msg text,
    ip_address text DEFAULT NULL::character varying,
    review_id bigint,
    add_uri_id bigint
);


--
-- Name: ki_updates_ki_update_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ki_updates_ki_update_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ki_updates_ki_update_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ki_updates_ki_update_id_seq OWNED BY public.ki_updates.ki_update_id;


--
-- Name: ki_years_cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_years_cache (
    year smallint DEFAULT '0'::smallint,
    region_id smallint DEFAULT '0'::smallint
);


--
-- Name: ki_zayavka_allrpg; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ki_zayavka_allrpg (
    allrpg_zayvka_id bigint NOT NULL,
    game_id bigint,
    name text,
    opened smallint DEFAULT '1'::smallint
);


--
-- Name: news; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.news (
    news_id bigint NOT NULL,
    news_date timestamp with time zone,
    news_author text DEFAULT ''::character varying,
    news_header text DEFAULT ''::character varying,
    news_text text
);


--
-- Name: news_news_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.news_news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: news_news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.news_news_id_seq OWNED BY public.news.news_id;


--
-- Name: news_tags; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.news_tags (
    nid bigint DEFAULT '0'::bigint NOT NULL,
    tid bigint DEFAULT '0'::bigint NOT NULL
);


--
-- Name: old_games; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.old_games (
    old_game_id bigint NOT NULL,
    game_date text,
    game_name text,
    game_region text,
    game_uri text
);


--
-- Name: old_games_old_game_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.old_games_old_game_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: old_games_old_game_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.old_games_old_game_id_seq OWNED BY public.old_games.old_game_id;


--
-- Name: privs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.privs (
    id bigint NOT NULL,
    name text DEFAULT ''::character varying,
    "desc" text DEFAULT ''::character varying,
    hidden_flag smallint DEFAULT '0'::smallint
);


--
-- Name: privs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.privs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: privs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.privs_id_seq OWNED BY public.privs.id;


--
-- Name: tags; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tags (
    id bigint NOT NULL,
    uri text DEFAULT ''::character varying,
    tag_name text DEFAULT ''::character varying
);


--
-- Name: tags_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tags_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tags_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tags_id_seq OWNED BY public.tags.id;


--
-- Name: user_privs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_privs (
    uid bigint DEFAULT '0'::bigint NOT NULL,
    pid bigint DEFAULT '0'::bigint NOT NULL
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    user_id bigint NOT NULL,
    username text DEFAULT ''::character varying,
    email text DEFAULT ''::character varying,
    lastvisit timestamp with time zone,
    create_date date,
    editor_flag smallint DEFAULT '0'::smallint
);


--
-- Name: users_user_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_user_id_seq OWNED BY public.users.user_id;


--
-- Name: ki_add_uri add_uri_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_add_uri ALTER COLUMN add_uri_id SET DEFAULT nextval('public.ki_add_uri_add_uri_id_seq'::regclass);


--
-- Name: ki_game_date game_date_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_game_date ALTER COLUMN game_date_id SET DEFAULT nextval('public.ki_game_date_game_date_id_seq'::regclass);


--
-- Name: ki_game_types game_type_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_game_types ALTER COLUMN game_type_id SET DEFAULT nextval('public.ki_game_types_game_type_id_seq'::regclass);


--
-- Name: ki_games id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_games ALTER COLUMN id SET DEFAULT nextval('public.ki_games_id_seq'::regclass);


--
-- Name: ki_photo photo_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_photo ALTER COLUMN photo_id SET DEFAULT nextval('public.ki_photo_photo_id_seq'::regclass);


--
-- Name: ki_polygons polygon_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_polygons ALTER COLUMN polygon_id SET DEFAULT nextval('public.ki_polygons_polygon_id_seq'::regclass);


--
-- Name: ki_regions region_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_regions ALTER COLUMN region_id SET DEFAULT nextval('public.ki_regions_region_id_seq'::regclass);


--
-- Name: ki_review review_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_review ALTER COLUMN review_id SET DEFAULT nextval('public.ki_review_review_id_seq'::regclass);


--
-- Name: ki_sub_regions sub_region_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_sub_regions ALTER COLUMN sub_region_id SET DEFAULT nextval('public.ki_sub_regions_sub_region_id_seq'::regclass);


--
-- Name: ki_update_types ki_update_type_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_update_types ALTER COLUMN ki_update_type_id SET DEFAULT nextval('public.ki_update_types_ki_update_type_id_seq'::regclass);


--
-- Name: ki_updates ki_update_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_updates ALTER COLUMN ki_update_id SET DEFAULT nextval('public.ki_updates_ki_update_id_seq'::regclass);


--
-- Name: news news_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.news ALTER COLUMN news_id SET DEFAULT nextval('public.news_news_id_seq'::regclass);


--
-- Name: old_games old_game_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.old_games ALTER COLUMN old_game_id SET DEFAULT nextval('public.old_games_old_game_id_seq'::regclass);


--
-- Name: privs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.privs ALTER COLUMN id SET DEFAULT nextval('public.privs_id_seq'::regclass);


--
-- Name: tags id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tags ALTER COLUMN id SET DEFAULT nextval('public.tags_id_seq'::regclass);


--
-- Name: users user_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN user_id SET DEFAULT nextval('public.users_user_id_seq'::regclass);


--
-- Name: ki_add_uri idx_16880_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_add_uri
    ADD CONSTRAINT idx_16880_primary PRIMARY KEY (add_uri_id);


--
-- Name: ki_games idx_16887_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_games
    ADD CONSTRAINT idx_16887_primary PRIMARY KEY (id);


--
-- Name: ki_game_date idx_16912_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_game_date
    ADD CONSTRAINT idx_16912_primary PRIMARY KEY (game_date_id);


--
-- Name: ki_game_types idx_16917_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_game_types
    ADD CONSTRAINT idx_16917_primary PRIMARY KEY (game_type_id);


--
-- Name: ki_photo idx_16926_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_photo
    ADD CONSTRAINT idx_16926_primary PRIMARY KEY (photo_id);


--
-- Name: ki_polygons idx_16936_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_polygons
    ADD CONSTRAINT idx_16936_primary PRIMARY KEY (polygon_id);


--
-- Name: ki_regions idx_16945_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_regions
    ADD CONSTRAINT idx_16945_primary PRIMARY KEY (region_id);


--
-- Name: ki_review idx_16953_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_review
    ADD CONSTRAINT idx_16953_primary PRIMARY KEY (review_id);


--
-- Name: ki_status idx_16959_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_status
    ADD CONSTRAINT idx_16959_primary PRIMARY KEY (status_id);


--
-- Name: ki_sub_regions idx_16967_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_sub_regions
    ADD CONSTRAINT idx_16967_primary PRIMARY KEY (sub_region_id);


--
-- Name: ki_updates idx_16975_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_updates
    ADD CONSTRAINT idx_16975_primary PRIMARY KEY (ki_update_id);


--
-- Name: ki_update_types idx_16983_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_update_types
    ADD CONSTRAINT idx_16983_primary PRIMARY KEY (ki_update_type_id);


--
-- Name: ki_zayavka_allrpg idx_16999_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ki_zayavka_allrpg
    ADD CONSTRAINT idx_16999_primary PRIMARY KEY (allrpg_zayvka_id);


--
-- Name: news idx_17006_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT idx_17006_primary PRIMARY KEY (news_id);


--
-- Name: news_tags idx_17014_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.news_tags
    ADD CONSTRAINT idx_17014_primary PRIMARY KEY (nid, tid);


--
-- Name: old_games idx_17020_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.old_games
    ADD CONSTRAINT idx_17020_primary PRIMARY KEY (old_game_id);


--
-- Name: privs idx_17027_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.privs
    ADD CONSTRAINT idx_17027_primary PRIMARY KEY (id);


--
-- Name: tags idx_17035_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tags
    ADD CONSTRAINT idx_17035_primary PRIMARY KEY (id);


--
-- Name: users idx_17042_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT idx_17042_primary PRIMARY KEY (user_id);


--
-- Name: user_privs idx_17049_primary; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_privs
    ADD CONSTRAINT idx_17049_primary PRIMARY KEY (uid, pid);


--
-- Name: idx_16880_resolved; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16880_resolved ON public.ki_add_uri USING btree (resolved);


--
-- Name: idx_16887_polygon; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16887_polygon ON public.ki_games USING btree (polygon);


--
-- Name: idx_16887_type; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16887_type ON public.ki_games USING btree (type);


--
-- Name: idx_16912_game_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16912_game_id ON public.ki_game_date USING btree (game_id, "order");


--
-- Name: idx_16917_show_all_regions; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16917_show_all_regions ON public.ki_game_types USING btree (show_all_regions);


--
-- Name: idx_16926_game_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16926_game_id ON public.ki_photo USING btree (game_id);


--
-- Name: idx_16945_region_experimental; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16945_region_experimental ON public.ki_regions USING btree (region_experimental);


--
-- Name: idx_16945_region_name; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_16945_region_name ON public.ki_regions USING btree (region_name, region_code);


--
-- Name: idx_16953_game_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16953_game_id ON public.ki_review USING btree (game_id);


--
-- Name: idx_16953_game_review; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16953_game_review ON public.ki_review USING btree (game_id, review_id);


--
-- Name: idx_16967_region_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16967_region_id ON public.ki_sub_regions USING btree (region_id);


--
-- Name: idx_16975_game_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16975_game_id ON public.ki_updates USING btree (game_id);


--
-- Name: idx_16975_photo_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16975_photo_id ON public.ki_updates USING btree (photo_id);


--
-- Name: idx_16975_update_date; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16975_update_date ON public.ki_updates USING btree (update_date, game_id);


--
-- Name: idx_16975_updated_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16975_updated_user_id ON public.ki_updates USING btree (updated_user_id);


--
-- Name: idx_16994_sub_region_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_16994_sub_region_id ON public.ki_years_cache USING btree (region_id);


--
-- Name: idx_16994_year; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_16994_year ON public.ki_years_cache USING btree (year, region_id);


--
-- Name: idx_17006_news_date; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_17006_news_date ON public.news USING btree (news_date);


--
-- Name: idx_17006_news_text; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_17006_news_text ON public.news USING gin (to_tsvector('simple'::regconfig, news_text));


--
-- Name: idx_17035_name; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_17035_name ON public.tags USING btree (tag_name);


--
-- Name: idx_17035_uri; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX idx_17035_uri ON public.tags USING btree (uri);


--
-- PostgreSQL database dump complete
--

\unrestrict YKQmch2d1TBx8do79bLQPwafEzlqXwjdriU98MMicVCSdM7cRIfJD306zDCENbT

