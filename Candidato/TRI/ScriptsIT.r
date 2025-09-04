## cargar paquetes necesarios ##
library(catR)
library(readxl)

# cargar datos 
#Miramos los argumentos
args <- commandArgs(TRUE)
#[1] -> Nombre del fichero generado con el Tipo de Razonamiento (Numérico[1], Verbal[2], Espacial[3], Lógico[4], Diagramático[5] ...) TRI
#se sustituye por el nombre de fichero ejemplo: pathTo "2.csv" para Verbal

file_name <- args[1]
data <- read.csv(file_name, header = TRUE, sep = ",", encoding = "utf-8")

# Creacion de la matriz para el banco de items de la tipología nips (with no content balancing)
banco_items <- as.matrix(data[,1:4])


# Siguiente Item - Quitando el anterior/es y con el patrón de respuestas
# respuestas -> x = c(aciertos = 1 y fallos = 0)
# items contestados ->  out = c(número de item, número de ítem)
nextItem(banco_items, x = c(1,0), out = c(38,7), criterion = "MEI")


#Necesario crear un objeto con las respuestas, en este ejemplo sería (acierto, error)
Respuestas <- c(1,0)

#Habilidad estimada
thetaEst(banco_items, Respuestas, method = "ML")

#Error estimado (-0.68... habilidad obtenida anterior)
eapSem(-1.058285, banco_items, Respuestas, model = NULL, D = 1, priorDist = "norm",
       priorPar = c(0,1), lower = -4, upper = 4, nqp = 33)

#Para finalizar la prueba, sería un error estimado de 0.4 
# o inferior o 30 ítems contestados.

#La puntuación se calcula así, donde la puntuación a otorgar
# a th es la habilidad estimada
#En el caso de todo errores sería 1 directamente 
# y de todo aciertos sería 99, 
#esto es importante ya que no lo calcula en esos casos.
th <- 0.774  
z <- (th*10 + 50)