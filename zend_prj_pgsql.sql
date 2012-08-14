--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.3
-- Dumped by pg_dump version 9.1.3
-- Started on 2012-03-23 13:48:53 WIT

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 173 (class 3079 OID 11907)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2198 (class 0 OID 0)
-- Dependencies: 173
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 161 (class 1259 OID 45843)
-- Dependencies: 2146 2147 6
-- Name: zf_groupmodules; Type: TABLE; Schema: public; Owner: developer; Tablespace: 
--

CREATE TABLE zf_groupmodules (
    groupmodule_id bigint NOT NULL,
    groupmodule_name character varying(255) DEFAULT NULL::character varying,
    is_locked smallint DEFAULT 0
);


ALTER TABLE public.zf_groupmodules OWNER TO developer;

--
-- TOC entry 162 (class 1259 OID 45848)
-- Dependencies: 6 161
-- Name: zf_groupmodules_groupmodule_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE zf_groupmodules_groupmodule_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.zf_groupmodules_groupmodule_id_seq OWNER TO developer;

--
-- TOC entry 2199 (class 0 OID 0)
-- Dependencies: 162
-- Name: zf_groupmodules_groupmodule_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE zf_groupmodules_groupmodule_id_seq OWNED BY zf_groupmodules.groupmodule_id;


--
-- TOC entry 2200 (class 0 OID 0)
-- Dependencies: 162
-- Name: zf_groupmodules_groupmodule_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('zf_groupmodules_groupmodule_id_seq', 12, true);


--
-- TOC entry 163 (class 1259 OID 45850)
-- Dependencies: 2149 2150 2151 2152 2153 2154 2155 2156 6
-- Name: zf_modules; Type: TABLE; Schema: public; Owner: developer; Tablespace: 
--

CREATE TABLE zf_modules (
    module_id bigint NOT NULL,
    module_name character varying(255) DEFAULT NULL::character varying,
    module_title character varying(255) DEFAULT NULL::character varying,
    sorted_number integer DEFAULT 1,
    groupmodule_id bigint DEFAULT 0,
    is_active smallint DEFAULT 0,
    parent_module bigint DEFAULT 0,
    is_core smallint DEFAULT 0,
    developer character varying(255) DEFAULT '-'::character varying
);


ALTER TABLE public.zf_modules OWNER TO developer;

--
-- TOC entry 164 (class 1259 OID 45864)
-- Dependencies: 163 6
-- Name: zf_modules_module_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE zf_modules_module_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.zf_modules_module_id_seq OWNER TO developer;

--
-- TOC entry 2201 (class 0 OID 0)
-- Dependencies: 164
-- Name: zf_modules_module_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE zf_modules_module_id_seq OWNED BY zf_modules.module_id;


--
-- TOC entry 2202 (class 0 OID 0)
-- Dependencies: 164
-- Name: zf_modules_module_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('zf_modules_module_id_seq', 48, true);


--
-- TOC entry 165 (class 1259 OID 45866)
-- Dependencies: 2158 6
-- Name: zf_moduleshow; Type: TABLE; Schema: public; Owner: developer; Tablespace: 
--

CREATE TABLE zf_moduleshow (
    moduleshow_id bigint NOT NULL,
    role_id bigint,
    access_type character varying(20) DEFAULT NULL::character varying,
    module_id bigint
);


ALTER TABLE public.zf_moduleshow OWNER TO developer;

--
-- TOC entry 166 (class 1259 OID 45870)
-- Dependencies: 6 165
-- Name: zf_moduleshow_moduleshow_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE zf_moduleshow_moduleshow_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.zf_moduleshow_moduleshow_id_seq OWNER TO developer;

--
-- TOC entry 2203 (class 0 OID 0)
-- Dependencies: 166
-- Name: zf_moduleshow_moduleshow_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE zf_moduleshow_moduleshow_id_seq OWNED BY zf_moduleshow.moduleshow_id;


--
-- TOC entry 2204 (class 0 OID 0)
-- Dependencies: 166
-- Name: zf_moduleshow_moduleshow_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('zf_moduleshow_moduleshow_id_seq', 19, true);


--
-- TOC entry 167 (class 1259 OID 45872)
-- Dependencies: 2160 6
-- Name: zf_moduleshow_override; Type: TABLE; Schema: public; Owner: developer; Tablespace: 
--

CREATE TABLE zf_moduleshow_override (
    moduleshow_override_id bigint NOT NULL,
    moduleshow_id bigint,
    role_id bigint,
    access_type character varying(20) DEFAULT NULL::character varying
);


ALTER TABLE public.zf_moduleshow_override OWNER TO developer;

--
-- TOC entry 168 (class 1259 OID 45876)
-- Dependencies: 6 167
-- Name: zf_moduleshow_override_moduleshow_override_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE zf_moduleshow_override_moduleshow_override_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.zf_moduleshow_override_moduleshow_override_id_seq OWNER TO developer;

--
-- TOC entry 2205 (class 0 OID 0)
-- Dependencies: 168
-- Name: zf_moduleshow_override_moduleshow_override_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE zf_moduleshow_override_moduleshow_override_id_seq OWNED BY zf_moduleshow_override.moduleshow_override_id;


--
-- TOC entry 2206 (class 0 OID 0)
-- Dependencies: 168
-- Name: zf_moduleshow_override_moduleshow_override_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('zf_moduleshow_override_moduleshow_override_id_seq', 15, true);


--
-- TOC entry 169 (class 1259 OID 45878)
-- Dependencies: 2162 2163 6
-- Name: zf_roles; Type: TABLE; Schema: public; Owner: developer; Tablespace: 
--

CREATE TABLE zf_roles (
    role_id bigint NOT NULL,
    role_name character varying(70) DEFAULT NULL::character varying,
    role_inherit bigint DEFAULT 0
);


ALTER TABLE public.zf_roles OWNER TO developer;

--
-- TOC entry 170 (class 1259 OID 45883)
-- Dependencies: 6 169
-- Name: zf_roles_role_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE zf_roles_role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.zf_roles_role_id_seq OWNER TO developer;

--
-- TOC entry 2207 (class 0 OID 0)
-- Dependencies: 170
-- Name: zf_roles_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE zf_roles_role_id_seq OWNED BY zf_roles.role_id;


--
-- TOC entry 2208 (class 0 OID 0)
-- Dependencies: 170
-- Name: zf_roles_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('zf_roles_role_id_seq', 8, true);


--
-- TOC entry 171 (class 1259 OID 45885)
-- Dependencies: 2165 2166 2167 6
-- Name: zf_users; Type: TABLE; Schema: public; Owner: developer; Tablespace: 
--

CREATE TABLE zf_users (
    user_id bigint NOT NULL,
    user_name character varying(255) DEFAULT NULL::character varying,
    passwd character varying(255) DEFAULT NULL::character varying,
    information text,
    is_active smallint DEFAULT (0)::smallint,
    role_id bigint
);


ALTER TABLE public.zf_users OWNER TO developer;

--
-- TOC entry 172 (class 1259 OID 45894)
-- Dependencies: 171 6
-- Name: zf_users_user_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE zf_users_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.zf_users_user_id_seq OWNER TO developer;

--
-- TOC entry 2209 (class 0 OID 0)
-- Dependencies: 172
-- Name: zf_users_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE zf_users_user_id_seq OWNED BY zf_users.user_id;


--
-- TOC entry 2210 (class 0 OID 0)
-- Dependencies: 172
-- Name: zf_users_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('zf_users_user_id_seq', 1, false);


--
-- TOC entry 2148 (class 2604 OID 45901)
-- Dependencies: 162 161
-- Name: groupmodule_id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_groupmodules ALTER COLUMN groupmodule_id SET DEFAULT nextval('zf_groupmodules_groupmodule_id_seq'::regclass);


--
-- TOC entry 2157 (class 2604 OID 45902)
-- Dependencies: 164 163
-- Name: module_id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_modules ALTER COLUMN module_id SET DEFAULT nextval('zf_modules_module_id_seq'::regclass);


--
-- TOC entry 2159 (class 2604 OID 45903)
-- Dependencies: 166 165
-- Name: moduleshow_id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_moduleshow ALTER COLUMN moduleshow_id SET DEFAULT nextval('zf_moduleshow_moduleshow_id_seq'::regclass);


--
-- TOC entry 2161 (class 2604 OID 45904)
-- Dependencies: 168 167
-- Name: moduleshow_override_id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_moduleshow_override ALTER COLUMN moduleshow_override_id SET DEFAULT nextval('zf_moduleshow_override_moduleshow_override_id_seq'::regclass);


--
-- TOC entry 2164 (class 2604 OID 45905)
-- Dependencies: 170 169
-- Name: role_id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_roles ALTER COLUMN role_id SET DEFAULT nextval('zf_roles_role_id_seq'::regclass);


--
-- TOC entry 2168 (class 2604 OID 45906)
-- Dependencies: 172 171
-- Name: user_id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_users ALTER COLUMN user_id SET DEFAULT nextval('zf_users_user_id_seq'::regclass);


--
-- TOC entry 2187 (class 0 OID 45843)
-- Dependencies: 161
-- Data for Name: zf_groupmodules; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY zf_groupmodules (groupmodule_id, groupmodule_name, is_locked) FROM stdin;
1	Administrator	1
2	NOTHAVEGROUP	1
12	Data Master	0
\.


--
-- TOC entry 2188 (class 0 OID 45850)
-- Dependencies: 163
-- Data for Name: zf_modules; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY zf_modules (module_id, module_name, module_title, sorted_number, groupmodule_id, is_active, parent_module, is_core, developer) FROM stdin;
1	zfmodwizard	Module Wizard	3	1	1	0	1	Abdul Malik Ikhsan
2	zfmodules	Modules	1	1	1	0	1	Abdul Malik Ikhsan
3	default	Default Module	1	2	1	0	1	Abdul Malik Ikhsan
123	zfusers	User Management	2	1	1	0	1	Abdul Malik Ikhsan
5	zfgroupmodules	Group Module	1	1	1	0	1	Abdul Malik Ikhsan
6	zfpriv	Privilege Control	3	1	1	0	1	Abdul Malik Ikhsan
\.


--
-- TOC entry 2189 (class 0 OID 45866)
-- Dependencies: 165
-- Data for Name: zf_moduleshow; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY zf_moduleshow (moduleshow_id, role_id, access_type, module_id) FROM stdin;
52	1	admin	123
3	2	view	3
47	1	admin	1
49	1	admin	6
48	1	admin	5
51	1	admin	2
\.


--
-- TOC entry 2190 (class 0 OID 45872)
-- Dependencies: 167
-- Data for Name: zf_moduleshow_override; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY zf_moduleshow_override (moduleshow_override_id, moduleshow_id, role_id, access_type) FROM stdin;
4	52	1	admin
10	52	4	view
\.


--
-- TOC entry 2191 (class 0 OID 45878)
-- Dependencies: 169
-- Data for Name: zf_roles; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY zf_roles (role_id, role_name, role_inherit) FROM stdin;
2	Everyone	0
1	admin	2
5	Developer	2
3	Forum Admin	2
4	Merchant	2
\.


--
-- TOC entry 2192 (class 0 OID 45885)
-- Dependencies: 171
-- Data for Name: zf_users; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY zf_users (user_id, user_name, passwd, information, is_active, role_id) FROM stdin;
1	admin	21232f297a57a5a743894a0e4a801fc3	-	1	1
\.


--
-- TOC entry 2172 (class 2606 OID 45916)
-- Dependencies: 163 163
-- Name: pk_modules_key_; Type: CONSTRAINT; Schema: public; Owner: developer; Tablespace: 
--

ALTER TABLE ONLY zf_modules
    ADD CONSTRAINT pk_modules_key_ PRIMARY KEY (module_id);


--
-- TOC entry 2170 (class 2606 OID 45920)
-- Dependencies: 161 161
-- Name: zf_groupmodules_pkey; Type: CONSTRAINT; Schema: public; Owner: developer; Tablespace: 
--

ALTER TABLE ONLY zf_groupmodules
    ADD CONSTRAINT zf_groupmodules_pkey PRIMARY KEY (groupmodule_id);


--
-- TOC entry 2176 (class 2606 OID 45922)
-- Dependencies: 167 167
-- Name: zf_moduleshow_override_pkey; Type: CONSTRAINT; Schema: public; Owner: developer; Tablespace: 
--

ALTER TABLE ONLY zf_moduleshow_override
    ADD CONSTRAINT zf_moduleshow_override_pkey PRIMARY KEY (moduleshow_override_id);


--
-- TOC entry 2174 (class 2606 OID 45924)
-- Dependencies: 165 165
-- Name: zf_moduleshow_pkey; Type: CONSTRAINT; Schema: public; Owner: developer; Tablespace: 
--

ALTER TABLE ONLY zf_moduleshow
    ADD CONSTRAINT zf_moduleshow_pkey PRIMARY KEY (moduleshow_id);


--
-- TOC entry 2178 (class 2606 OID 45926)
-- Dependencies: 169 169
-- Name: zf_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: developer; Tablespace: 
--

ALTER TABLE ONLY zf_roles
    ADD CONSTRAINT zf_roles_pkey PRIMARY KEY (role_id);


--
-- TOC entry 2180 (class 2606 OID 45928)
-- Dependencies: 171 171
-- Name: zf_users_pkey; Type: CONSTRAINT; Schema: public; Owner: developer; Tablespace: 
--

ALTER TABLE ONLY zf_users
    ADD CONSTRAINT zf_users_pkey PRIMARY KEY (user_id);


--
-- TOC entry 2181 (class 2606 OID 45949)
-- Dependencies: 163 161 2169
-- Name: fk_modules; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_modules
    ADD CONSTRAINT fk_modules FOREIGN KEY (groupmodule_id) REFERENCES zf_groupmodules(groupmodule_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2184 (class 2606 OID 45954)
-- Dependencies: 165 2173 167
-- Name: fk_roles_modshow_override; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_moduleshow_override
    ADD CONSTRAINT fk_roles_modshow_override FOREIGN KEY (moduleshow_id) REFERENCES zf_moduleshow(moduleshow_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2182 (class 2606 OID 45959)
-- Dependencies: 169 2177 165
-- Name: fk_roles_moduleshow; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_moduleshow
    ADD CONSTRAINT fk_roles_moduleshow FOREIGN KEY (role_id) REFERENCES zf_roles(role_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2185 (class 2606 OID 45964)
-- Dependencies: 2177 167 169
-- Name: fkey_roles_moduleshow; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_moduleshow_override
    ADD CONSTRAINT fkey_roles_moduleshow FOREIGN KEY (role_id) REFERENCES zf_roles(role_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2186 (class 2606 OID 45969)
-- Dependencies: 171 2177 169
-- Name: user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_users
    ADD CONSTRAINT user_fkey FOREIGN KEY (role_id) REFERENCES zf_roles(role_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2183 (class 2606 OID 45974)
-- Dependencies: 163 165 2171
-- Name: zf_moduleshow_module_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY zf_moduleshow
    ADD CONSTRAINT zf_moduleshow_module_id_fkey FOREIGN KEY (module_id) REFERENCES zf_modules(module_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2197 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-03-23 13:48:53 WIT

--
-- PostgreSQL database dump complete
--

