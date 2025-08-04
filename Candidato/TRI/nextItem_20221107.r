## cargar paquetes necesarios ##
library(catR)
library(readxl)
library(stringr)

# cargar datos 
#Miramos los argumentos
args <- commandArgs(TRUE)
#[1] -> Tipo de Razonamiento (Numérico[1], Verbal[2], Espacial[3], Lógico[4], Diagramático[5] ...) TRI, se sustituye por el nombre de fichero ejemplo: pathTo "2.csv"
#[2] -> Respuestas - Array de aciertos y errores del tipo [1,1,0,1,0,0,1] - aciertos = 1 y fallos = 0
#[3] -> Respuestas_index - Array de indice de respuestas de la matriz del tipo [2,14,20,31,40,35,27] (fila del csv o Excel)
#PTE [4] num_preguntas_max número de preguntas máximas de la prueba.
fType <- args[1]
fName <- paste(fType, "csv", sep = ".")
data <- read_csv2(fName)

#Necesario crear un objeto con las respuestas.
inRespuestas <- args[2]
Respuestas <- c(strsplit(inRespuestas, split=","))
#Necesario crear un objeto los índices de la matriz de respuestas.
inRespuestas_index <- args[3]
Respuestas_index <- c(strsplit(inRespuestas_index, split=","))

# Selección de la matriz para el banco de items desde la fila 1 - 4
Banco_items <- as.matrix(data[,1:4])

# Siguiente Item - Quitando el anterior/es y con el patrón de respuestas
# respuestas -> x = c(aciertos = 1 y fallos = 0)
# items contestados ->  out = c(número de item, número de ítem) -> No es número de item, es Índice del item dentro del array de Excel 
next_item <- nextItem(Banco_items, x = Respuestas, out = Respuestas_index, criterion = "MEI")




#Habilidad estimada
habilidad_estimada <- thetaEst(Banco_items, Respuestas, method = "ML")


#Error estimado (-0.68... habilidad obtenida anterior)

error_estimado <- eapSem(habilidad_estimada, Banco_items, Respuestas, model = NULL, D = 1, priorDist = "norm",
       priorPar = c(0, 1), lower = -4, upper = 4, nqp = 33)

if (error_estimado <= 0.4 || Respuestas_index > num_preguntas_max){
	#La puntuación se calcula así, donde la puntuación a otorgar a th es la habilidad estimada
	
	#En el caso de todo errores sería 1 directamente y de todo aciertos sería 99, 
	#esto es importante ya que no lo calcula en esos casos. 
	z <- (habilidad_estimada*10 + 50) #puntuación final del candidato
	if (Respuestas == todos errores){
		z <- 1
	else if (Respuestas == todos aciertos){
		z <- 99
	}
	prunt_r(z)
}else{
	print_r(next_item)
}
#Para finalizar la prueba, sería un error estimado de 0.4 o inferior o 30 ítems contestados.

