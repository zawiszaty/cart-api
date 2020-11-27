{{/* vim: set filetype=mustache: */}}
{{/*
Expand the name of the chart.
*/}}
{{- define "name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Create a default fully qualified app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
*/}}
{{- define "fullname" -}}
{{- $name := default .Chart.Name .Values.nameOverride -}}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Create chart name and version as used by the chart label.
*/}}
{{- define "chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" -}}
{{- end -}}


{{/* Helm required labels */}}
{{- define "labels" -}}
heritage: {{ .Release.Service }}
release: {{ .Release.Name }}
chart: {{ .Chart.Name }}
app: "{{ template "name" . }}"
{{- end -}}

{{/* matchLabels */}}
{{- define "matchLabels" -}}
release: {{ .Release.Name }}
app: "{{ template "name" . }}"
{{- end -}}

{{- define "php" -}}
  {{- printf "%s-php" (include "fullname" .) -}}
{{- end -}}

{{- define "fpm" -}}
  {{- printf "%s-fpm" (include "fullname" .) -}}
{{- end -}}

{{- define "supervisor" -}}
  {{- printf "%s-supervisor" (include "fullname" .) -}}
{{- end -}}

{{- define "nginx" -}}
  {{- printf "%s-nginx" (include "fullname" .) -}}
{{- end -}}

{{- define "hook" -}}
    {{- printf "%s-hook" (include "fullname" .) -}}
{{- end -}}
