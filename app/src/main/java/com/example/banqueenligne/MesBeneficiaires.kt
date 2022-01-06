package com.example.banqueenligne

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.view.Window
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.*
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.flexbox.FlexboxLayout
import org.json.JSONArray
import org.json.JSONObject


class MesBeneficiaires : AppCompatActivity(), View.OnClickListener {
    lateinit var mQueue : RequestQueue

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        setContentView(R.layout.activity_mes_beneficiaires)
        mQueue = Volley.newRequestQueue(this)

        listBeneficiaires()

        //actionbar
        val actionbar = supportActionBar
        //set actionbar title
        actionbar!!.title = "Mes bénéficiaires"
        //set back button
        actionbar.setDisplayHomeAsUpEnabled(true)

    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }

    fun setBeneficiaires(beneficiaires: JSONArray){

        for(i in 0 until beneficiaires.length()){
            val parent = findViewById<FlexboxLayout>(R.id.list_beneficiaire)
            val child : View = layoutInflater.inflate(R.layout.display_beneficiaires, parent, false)
            val box = child.findViewById<FlexboxLayout>(R.id.displayBeneficiaire)

            val name : String = beneficiaires!!.getJSONObject(i).optString("name")
            val firstname : String = beneficiaires!!.getJSONObject(i).optString("first_name")
            val rib : String = beneficiaires!!.getJSONObject(i).optString("rib")


            child.findViewById<TextView>(R.id.name).text = concat(name, " ", firstname).toUpperCase()
            child.findViewById<TextView>(R.id.rib).text = rib

            parent.addView(child)

            box.setOnClickListener(this)

            val addBeneficiaires = findViewById<Button>(R.id.btn_add_beneficiaire)
            addBeneficiaires.setOnClickListener(this)
        }

    }

    private fun concat(vararg string: String): String {
        val sb = StringBuilder()
        for (s in string) {
            sb.append(s)
        }

        return sb.toString()
    }

    override fun onClick(view: View) {
        when (view.getId()) {
            R.id.displayBeneficiaire -> displayBeneficiaire()
            R.id.btn_add_beneficiaire -> startActivity(
                Intent(
                    this@MesBeneficiaires,
                    AddBeneficiaire::class.java
                )
            )
        }
    }

    fun displayBeneficiaire(){
        val nextActivity = Intent(this@MesBeneficiaires, DetailBeneficiaire::class.java)
        nextActivity.putExtra("name", findViewById<TextView>(R.id.name).text)
        nextActivity.putExtra("rib", findViewById<TextView>(R.id.rib).text)
        startActivity(nextActivity)
    }

    fun listBeneficiaires() {
        val url = "http://192.168.1.26/onlineBankAPI/v1/?op=getBeneficiaires" //IP A CHANGER
        lateinit var data : JSONObject
        lateinit var beneficiaires : JSONArray

        val request = object : StringRequest(
            Request.Method.POST, url,
            Response.Listener<String> { response ->
                data = JSONObject(response)
                beneficiaires = data.optJSONArray("message")
                setBeneficiaires(beneficiaires)
            },
            Response.ErrorListener { error: VolleyError ->
                println(error)
            }
        ){
            @Throws(AuthFailureError::class)
            override fun getParams() : Map<String, String> {
                val params = HashMap<String, String>()
                params["user_id"] = "2"
                return params
            }
        }

        mQueue.add(request)
    }
}