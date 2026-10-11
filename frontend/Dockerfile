FROM node:18 AS build
WORKDIR /app
COPY . .
ARG VITE_API_BASE=http://localhost:8000/api
ENV VITE_API_BASE=$VITE_API_BASE
RUN npm install && npm run build

FROM nginx:alpine
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
