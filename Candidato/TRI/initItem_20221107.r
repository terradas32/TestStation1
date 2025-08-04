## cargar paquetes necesarios ##
library(catR)
library(readxl)

# cargar datos 
#Miramos los argumentos
args <- commandArgs(TRUE)
#[1] -> Nombre del fichero generado con el Tipo de Razonamiento (Numérico[1], Verbal[2], Espacial[3], Lógico[4], Diagramático[5] ...) TRI
#se sustituye por el nombre de fichero ejemplo: pathTo "tri_2.csv"
file_name <- args[1]
# fName <- paste(file_name, "csv", sep = ".") le concatena la extensión
data <- read.csv(file_name, header = TRUE, sep = ",", encoding = "utf-8")
#print(data)
#data <- read_csv2(fName)

# Creacion de la matriz para el banco de items de la tipología nips (with no content balancing)
banco_items <- as.matrix(data[,1:4])
#print(banco_items)

# Generar Items Iniciales
start_item <- startItems(banco_items, theta = c(-1, 0, 1), randomesque = 3)
print(start_item)