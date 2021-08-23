export default {
  indexById(arr) {
    if(arr == null){
      return {
        data:{},
        order:[],
        isLoaded:false
      }
    }
    return {
      data:arr.reduce((map,obj)=>{map[obj.id]=obj;return map},{}),
      order:arr.map(obj=>obj.id),
      isLoaded:true
    }
  },
  ordered(obj){
    return obj.order.map(order=>obj.data[order])
  }

}
