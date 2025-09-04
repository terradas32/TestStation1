## cargar paquetes necesarios ##
library(catR)
library(readxl)

# cargar datos 
#Miramos los argumentos
args <- commandArgs(TRUE)
#[1] -> Nombre del fichero generado con el Tipo de Razonamiento (Numérico[1], Verbal[2], Espacial[3], Lógico[4], Diagramático[5] ...) TRI
#se sustituye por el nombre de fichero ejemplo: pathTo "2.csv" para Verbal
#[2] -> in_respuestas - Array de aciertos y errores del tipo [1,1,0,1,0,0,1] - aciertos = 1 y fallos = 0
#[3] -> in_respuestas_index - Array de indice de respuestas de la matriz del tipo [2,14,20,31,40,35,27] (fila del csv o Excel)
#[4] -> num_preguntas_max número de preguntas máximas de la prueba.
#[5] -> Tipo de Prueba == Razonamiento (Verbal, numérico etc).


file_name <- args[1]
data <- read.csv(file_name, header = TRUE, sep = ",", encoding = "utf-8")

# Selección de la matriz para el banco de items desde la fila 1 - 4
banco_items <- as.matrix(data[,1:4])

#Necesario crear un objeto con las respuestas.
in_respuestas <- args[2]
respuestas <- c(as.numeric(strsplit(in_respuestas, ",")[[1]]))

#Necesario crear un objeto los índices de la matriz de respuestas.
in_respuestas_index <- args[3]
respuestas_index <- c(as.numeric(strsplit(in_respuestas_index, ",")[[1]]))

num_preguntas_max <- as.numeric(args[4])

#Tipo de Razonamiento de la prueba.
tipo_razonamiento <- as.numeric(args[5])

# Siguiente Item - Quitando el anterior/es y con el patrón de respuestas
# respuestas -> x = c(aciertos = 1 y fallos = 0)
# items contestados ->  out = c(número de item, número de ítem) -> No es número de item, es Índice del item dentro del array de Excel
next_item <- nextItem(banco_items, x = respuestas, out = respuestas_index, criterion = "MEI")
#print(next_item$item)

#Habilidad estimada
habilidad_estimada <- thetaEst(banco_items[respuestas_index,], respuestas, method = "ML")

#Error estimado (-0.68... habilidad obtenida anterior)
error_estimado <- eapSem(habilidad_estimada, banco_items[respuestas_index,], respuestas, model = NULL, D = 1, priorDist = "norm",
       priorPar = c(0, 1), lower = -4, upper = 4, nqp = 33)

#Para finalizar la prueba, sería un error estimado de 0.4 o inferior o 30 ítems contestados (depende de la prueba).
error_estimado_limite <- 0
if (tipo_razonamiento == 1){
	error_estimado_limite <- 0.4 
}else{
	error_estimado_limite <- 0.7
}
if (error_estimado <= error_estimado_limite || length(respuestas_index) >= num_preguntas_max){
	#La puntuación se calcula así, donde la puntuación a otorgar a th es la habilidad estimada
	#En el caso de todo errores sería 1 directamente y de todo aciertos sería 99, 
	#esto es importante ya que no lo calcula en esos casos. 
	z <- (habilidad_estimada*10 + 50) #puntuación final del candidato
	if (sum(respuestas) == 0){
		z <- 1
	}else if (sum(respuestas) == length(respuestas)){
		z <- 99
	}
	print(z)
}else{
	print(next_item)
}
