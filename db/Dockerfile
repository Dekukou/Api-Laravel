FROM postgres

ENV POSTGRES_USER myApi
ENV POSTGRES_PASSWORD myApi
ENV POSTGRES_DB myApi

COPY create_table.sql /docker-entrypoint-initdb.d/create_table.sql
