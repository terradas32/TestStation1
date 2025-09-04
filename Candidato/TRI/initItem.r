## cargar paquetes necesarios ##
library(catR)
library(readxl)

# cargar datos 
#Miramos los argumentos
args <- commandArgs(TRUE)
#[1] -> Nombre del fichero generado con el Tipo de Razonamiento (Numérico[1], Verbal[2], Espacial[3], Lógico[4], Diagramático[5] ...) TRI
#se sustituye por el nombre de fichero ejemplo: pathTo "es_2.csv" para Verbal
#[2] -> Tipo de Prueba == Razonamiento (Verbal, numérico etc).

file_name <- args[1]
data <- read.csv(file_name, header = TRUE, sep = ",", encoding = "utf-8")

#Tipo de Razonamiento de la prueba.
tipo_razonamiento <- as.numeric(args[2])

# Creacion de la matriz para el banco de items de la tipología nips (with no content balancing)
banco_items <- as.matrix(data[,1:4])

# Generar 3 Items Iniciales para NIPS
# Generar 7 Items Iniciales para VIPS
if (tipo_razonamiento == 1){
    start_item <- startItems(banco_items, theta = c(-1, 0, 1), randomesque = 3)
}else{
    start_item <- startItems(banco_items, seed = NA, nrItems = 7)
    
}
print(start_item)